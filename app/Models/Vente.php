<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'date_vente', 'montant',
        'quantite', 'client_id', 'produit_id',
    ];

    // Une vente APPARTIENT a un client (cle etrangere : client_id)
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Une vente APPARTIENT a un produit (cle etrangere : produit_id)
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    // Genere le code automatique : V-0001-2026-08-14
    public static function genererCode(): string
    {
        $numero = str_pad(self::count() + 1, 4, '0', STR_PAD_LEFT);
        return 'V-' . $numero . '-' . now()->toDateString();
    }
}