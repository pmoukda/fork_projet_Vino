<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['sku','name', 'price', 'image', 'millesime_produit', 'pays_origine', 'couleur', 'identite_produit'];
}
