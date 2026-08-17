<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Produit;
use App\Models\Vente;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. L'administrateur et les categories (seeders existants)
        $this->call([
            AdminUserSeeder::class,
            CategorieSeeder::class,
        ]);

        // 2. Les produits AVANT les ventes (contrainte de cle etrangere !)
        Produit::factory()->count(30)->create();

        // 3. Les clients
        Client::factory()->count(50)->create();

        // 4. Les ventes EN DERNIER (elles referencent clients ET produits)
        Vente::factory()->count(200)->create();
    }
}