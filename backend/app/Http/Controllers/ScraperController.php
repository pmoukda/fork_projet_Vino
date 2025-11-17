<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ScraperController extends Controller
{
    protected $endpoint;
    protected $headers;

    public function __construct()
    {
        $this->endpoint = config('services.saq.graphql_url');
        $this->headers = [
            'x-api-key' => config('services.saq.api_key'),
            'Magento-Environment-Id' => config('services.saq.env_id'),
            'magento-store-code' => config('services.saq.store_code'),
            'magento-store-view-code' => config('services.saq.store_view'),
            'magento-website-code' => config('services.saq.website_code'),
            'Content-Type' => 'application/json',
            'User-Agent' => 'Laravel-Scraper/1.0',
        ];
    }

    // GraphQL query de base pour récupérer les produits
    protected function productSearchQuery()
    {
        return <<<'GRAPHQL'
query productSearch($phrase: String!, $pageSize: Int, $currentPage: Int = 1, $filter: [SearchClauseInput!], $context: QueryContextInput) {
    productSearch(phrase: $phrase, page_size: $pageSize, current_page: $currentPage, filter: $filter, context: $context) {
        total_count
        items {
            product {
                name
                sku
                image { url }
                small_image { url }
                thumbnail { url }
                price_range {
                    minimum_price {
                        final_price { value currency }
                    }
                }
            }
            productView {
                attributes {
                    name
                    value
                    label
                }
            }
        }
    }
}
GRAPHQL;
    }

    // Fonction pour scraper une page donnée
    protected function scrapePage(int $pageSize, int $currentPage, array $filters = [])
    {
        $variables = [
            'phrase' => '',
            'pageSize' => $pageSize,
            'currentPage' => $currentPage,
            'filter' => $filters,
            'context' => [
                'customerGroup' => 'b6589fc6ab0dc82cf12099d1c2d40ab994e8410c',
                'userViewHistory' => []
            ]
        ];

        $attempts = 0;
        do {
            $attempts++;
            $response = Http::withHeaders($this->headers)
                            ->timeout(20)
                            ->withOptions(['verify' => false])
                            ->post($this->endpoint, [
                                'query' => $this->productSearchQuery(),
                                'variables' => $variables
                            ]);

            if ($response->status() == 429) {
                $wait = $response->header('Retry-After', 2);
                sleep(max(1, (int)$wait));
            } else {
                break;
            }
        } while ($attempts < 3);

        if (!$response->successful()) {
            Log::error('Erreur API GraphQL', ['status' => $response->status(), 'body' => $response->body()]);
            return ['items' => [], 'total_count' => 0];
        }

        $json = $response->json();
        $items = $json['data']['productSearch']['items'] ?? [];
        $total = $json['data']['productSearch']['total_count'] ?? 0;

        // Sauvegarde dans la DB
        foreach ($items as $entry) {
            $product = $entry['product'] ?? null;
            if (!$product) continue;

            $image = $product['image']['url'] ?? $product['small_image']['url'] ?? $product['thumbnail']['url'] ?? null;
            $price = $product['price_range']['minimum_price']['final_price']['value'] ?? null;

            // Fusion des attributs pour récupérer tous les champs
            $attr1 = collect($product['custom_attributes'] ?? [])->pluck('value', 'code');
            $attr2 = collect($entry['productView']['attributes'] ?? [])->pluck('value', 'name');
            //dd($entry['productView']['attributes'] ?? []);
            //dd($product['custom_attributes']);
            $allAttr = $attr1->merge($attr2);
            
            $sku = $product['sku'] ?? ($allAttr['sku'] ?? 'no-sku-' . md5($product['name']));
            $vintage  = $allAttr['millesime_produit'] ?? null;
            $country  = $allAttr['pays_origine'] ?? $allAttr['saq_country'] ?? null;
            
            $color    = $allAttr['couleur'] ?? $allAttr['saq_coulor'] ?? null;
            $identity = $allAttr['identite_produit'] ?? $allAttr['saq_identite'] ?? null;

            Log::info('Attributs fusionnés', $allAttr->toArray());

            Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name' => $product['name'],
                    'image' => $image,
                    'price' => $price,
                    'millesime_produit' => $vintage,
                    'pays_origine' => $country,
                    'couleur' => $color,
                    'identite_produit' => $identity,
                ]
            );
        }

        return ['items' => $items, 'total_count' => $total];
    }

    // Scraper de test limité à 50 vins rouges
    public function scrapeTest()
    {
       /* $filters = [
            //['attribute' => 'categoryPath', 'eq' => 'produits/vin/vin-rouge'],
            ['attribute' => 'categoryPath', 'like' => 'produits/vin%'],
            ['attribute' => 'availability_front', 'in' => ['En ligne', 'En succursale']],
            ['attribute' => 'visibility', 'in' => ['Catalog','Catalog, Search']]
        ];*/

        $filters = [
            ['attribute' => 'categoryPath', 'in' => [
                'produits/vin/vin-rouge',
                'produits/vin/vin-blanc',
                'produits/vin/vin-orange',
                'produits/vin/vin-rose',
                'produits/vin/vin-nature',
            ]],
            ['attribute' => 'availability_front', 'in' => ['En ligne', 'En succursale']],
            ['attribute' => 'visibility', 'in' => ['Catalog','Catalog, Search']]
        ];

        // Scraper une grande quantite de vins sur plusieurs pages
        
        $allItems = collect();
        $page = 1;
        $remaining = 200;

        while ($remaining > 0) {
            $take = min(50, $remaining);

            $res = $this->scrapePage($take, $page, $filters);

            if (empty($res['items'])) break;

            $allItems = $allItems->merge($res['items']);

            $remaining -= count($res['items']);
            $page++;
        }

        return response()->json([
            'message' => 'Scrape TEST terminé',
            'count' => count($res['items']),
            'items' => $res['items'],
        ]);
    }

    // Scraper complet pour tout le catalogue (attention au volume)
    public function scrapeAll(Request $request)
    {
        $pageSize = (int) $request->input('pageSize', 50);
        $currentPage = 1;
        $total = null;

        $filters = [
            ['attribute' => 'availability_front', 'in' => ['En ligne', 'En succursale']],
            ['attribute' => 'visibility', 'in' => ['Catalog','Catalog, Search']]
        ];

        do {
            $res = $this->scrapePage($pageSize, $currentPage, $filters);
            $total = $res['total_count'] ?? $total;
            $currentPage++;
            if ($currentPage > 500) break; // sécurité
        } while (count($res['items'] ?? []) && ($currentPage - 1) * $pageSize < ($total ?? 999999));

        return response()->json([
            'message' => 'Scrape complet terminé',
            'total' => $total
        ]);
    }
}