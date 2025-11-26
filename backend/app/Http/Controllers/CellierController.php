<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Cellier;

class CellierController extends Controller
{
   
    public function produits($cellierId) {
        $celliers = Cellier::with('produits')->findOrFail($cellierId);
        return response()->json($celliers->produits);
    }

    public function index(Request $request) {
        $user = $request->user(); // utilisateur connecté via Sanctum
        $celliers = Cellier::with('produits')->where('user_id', $user->id)->get();
        return response()->json($celliers);
 main
    }

    // Ajouter une nouvelle méthode pour créer un cellier via l'API
    public function storeAPI(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
        ]);

        $cellier = Cellier::create([
            'nom' => $validated['nom'],
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'message' => 'Cellier créé avec succès.',
            'cellier' => $cellier
        ], 201);
    }


    }    
 main






    public function afficherProduit($cellierId)
    {
        $cellier = Cellier::with('produits')->findOrFail($cellierId);
        return response()->json($cellier);
    } 
    
    public function ajouterProduit(Request $request, $cellierId) {
        $cellier = Cellier::findOrFail($cellierId);
        $produitId = $request->input('produit_id');
        $quantite = $request->input('quantite', 1);

        $item = $cellier->produits()->where('produit_id', $produitId)->first();

        if ($item) {
            $cellier->produits()->updateExistingPivot($produitId, [
                'quantite' => $item->pivot->quantite + $quantite
            ]);
        } else {
            $cellier->produits()->attach($produitId, ['quantite' => $quantite]);
        }

        return response()->json($cellier->produits()->where('produit_id', $produitId)->first());
    }


    public function modifierQuantite(Request $request, $cellierId, $produitId)
    {
        $quantite = $request->input('quantite', 1);
        $cellier = Cellier::findOrFail($cellierId);

        $cellier->produits()->updateExistingPivot($produitId, ['quantite' => $quantite]);

        return response()->json(['message' => 'Quantité mise à jour']);
    }

    public function supprimerProduit(Request $request, $cellierId, $produitId)
    {
        $cellier = Cellier::findOrFail($cellierId);
        $item = $cellier->produits()->where('produit_id', $produitId)->first();

        if (!$item) return response()->json(null, 404);

        if ($item->pivot->quantite <= 1) {
            $cellier->produits()->detach($produitId);
            return response()->json(null);
        } else {
            $cellier->produits()->updateExistingPivot($produitId, [
                'quantite' => $item->pivot->quantite - 1
            ]);
            return response()->json($cellier->produits()->where('produit_id', $produitId)->first());
        }
    }
}
