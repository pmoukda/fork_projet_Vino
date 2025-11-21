<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cellier extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nom', 'description'];

    // Un cellier appartient à un utilisateur
    public function user()
    {
        return $this->belongsToMany(Produit::class, 'cellier_produit')
                ->withPivot('quantite')
                ->withTimestamps();
    }

    // Relation avec les produits si besoin plus tard
    public function produits() {
    return $this->belongsToMany(Produit::class, 'cellier_produit')
                ->withPivot('quantite')
                ->withTimestamps();
}
}
