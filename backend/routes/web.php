<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\WineController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// routes/web.php
//Route::get('/scrape-saq', [ScraperController::class, 'scrapeAndSave']);

//Route::get('/scrape-saq', [ScraperController::class, 'ScrapeWines']);
Route::get('/wines', [WineController::class, 'index'])->name('wines.index');
Route::get('/scrape', [ScraperController::class, 'scrape']);

//Route::get('/scrape-all', [ScraperController::class, 'scrapeAll']);
Route::get('/scrape-test', [ScraperController::class, 'scrapeTest']);

/*Route::get('/test-scraping', function () {
    // Lire le fichier HTML sauvegardé
    $html = file_get_contents(storage_path('app/test.html'));

    // Extraire le JSON-LD
    preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $jsonMatch);
    $data = json_decode($jsonMatch[1] ?? '{}', true);

    // Construire le tableau produit
    $products = [
        'name' => $data['name'] ?? null,
        'price' => $data['offers']['price'] ?? null,
        'country' => $data['additionalProperty'][0]['value'] ?? null,
        'vintage' => $data['additionalProperty'][1]['value'] ?? null,
        'image' => $data['image'] ?? null,
    ];

    return response()->json($products);
});*/
