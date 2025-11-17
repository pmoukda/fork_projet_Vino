<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class WineController extends Controller
{
    public function index(Request $request) {
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
    }
}