<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Alimentation', 'description' => 'Produits alimentaires de base'],
            ['nom' => 'Boissons', 'description' => 'Boissons et liquides'],
            ['nom' => 'Hygiene', 'description' => 'Produits d\'hygiene et de proprete'],
            ['nom' => 'Electronique', 'description' => 'Appareils et accessoires electroniques'],
            ['nom' => 'Autres', 'description' => 'Produits divers'],
        ];

        foreach ($categories as $categorie) {
            // updateOrCreate : cree la categorie, ou la met a jour si elle existe deja
            // --> pas de doublons si on relance le seeder !
            Categorie::updateOrCreate(
                ['nom' => $categorie['nom']],
                $categorie
            );
        }
    }
}