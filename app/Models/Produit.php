<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'nom', 'description', 'prix',
        'quantite_stock', 'unite', 'categorie_id',
    ];

    // Un produit APPARTIENT a une categorie (cle etrangere : categorie_id)
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    // Un produit peut etre vendu PLUSIEURS fois
    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }

    // Genere le code automatique : P-0001-2026-08-14
    public static function genererCode(): string
    {
        $numero = str_pad(self::count() + 1, 4, '0', STR_PAD_LEFT);
        return 'P-' . $numero . '-' . now()->toDateString();
    }

    // Verifie si le stock est faible (pour l'alerte sur l'accueil)
    public function estEnStockFaible(): bool
    {
        return $this->quantite_stock <= 5;
    }
}