<?php

namespace Database\Factories;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    public function definition(): array
    {
        // Des noms de produits realistes pour Madagascar
        $types = ['Riz', 'Huile', 'Sucre', 'Farine', 'Savon', 'Lait', 'Cafe', 'The', 'Pates', 'Sel', 'Jus', 'Eau minerale'];
        $qualites = ['premium', 'standard', 'bio', 'extra', 'classique'];

        $nom = $types[array_rand($types)] . ' ' . $qualites[array_rand($qualites)];

        return [
            'code' => Produit::genererCode(),
            'nom' => $nom,
            'description' => $this->faker->sentence(6),
            'prix' => $this->faker->randomFloat(2, 1000, 50000),
            'quantite_stock' => $this->faker->numberBetween(50, 300),
            'unite' => $this->faker->randomElement(['kg', 'litre', 'unite', 'nombre']),
            'categorie_id' => Categorie::inRandomOrder()->first()->id,
        ];
    }
}