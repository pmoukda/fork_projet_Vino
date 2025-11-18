<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class ProductController extends Controller
{
   /* public function index(Request $request) {
        $couleur = $request->get('couleur');

        $query = Product::query();

        if ($couleur) {
            $query->where('couleur', 'like', "%{$couleur}%");
        }

        $query->where('couleur', 'like', "%{$couleur}%");

        $wines = $query->paginate(12);

        $categories = Product::whereNotNull('couleur')
                            ->distinct()
                            ->pluck('couleur');
        return view('wines.index', compact('wines', 'categories', 'couleur'));
    }*/

        /**
         * @param
         * Fonction qui affiche toutes les bouteilles du catalogue, si la bouteille a une couleur, on l'affiche. On ajoute une pagination de 12 bouteilles par page, on peut avancer, reculer, aller à la fin ou au début de la pagination.
         */

        public function index(Request $request) {
        $couleur = $request->get('couleur');

        $query = Product::query();

        if ($couleur) {
            $query->where('couleur', 'like', "%{$couleur}%");
        }

        try {

        $wines = $query->paginate(12); // Laravel pagination

        // Retour JSON pour React
        return response()->json([
            'data' => $wines->items(), // produits actuels
            'current_page' => $wines->currentPage(),
            'last_page' => $wines->lastPage(),
            'per_page' => $wines->perPage(),
            'total' => $wines->total(),
        ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
    /**
     * @param
     * Fonction qui affiche les détails d'une bouteille par son id
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }
        return response()->json($product);
    }
}