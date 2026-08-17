<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Produit;
use App\Models\Vente;
use Illuminate\Database\Eloquent\Factories\Factory;

class VenteFactory extends Factory
{
    protected $model = Vente::class;

    public function definition(): array
    {
        // On prend un produit et un client au hasard
        $produit = Produit::inRandomOrder()->first();
        $client = Client::inRandomOrder()->first();

        // Date aleatoire dans les 6 derniers mois (pour remplir le graphique)
        $dateVente = $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d');

        // Quantite raisonnable entre 1 et 5
        $quantite = $this->faker->randomFloat(2, 1, 5);

        return [
            'code' => Vente::genererCode($dateVente),
            'date_vente' => $dateVente,
            'quantite' => $quantite,
            'montant' => $quantite * $produit->prix,
            'client_id' => $client->id,
            'produit_id' => $produit->id,
        ];
    }

    // Cette methode s'execute APRES chaque creation de vente
    public function configure(): static
    {
        return $this->afterCreating(function (Vente $vente) {
            // Comme dans la vraie application : chaque vente decremente le stock
            $nouveauStock = max(0, $vente->produit->quantite_stock - $vente->quantite);
            $vente->produit->update(['quantite_stock' => $nouveauStock]);
        });
    }
}