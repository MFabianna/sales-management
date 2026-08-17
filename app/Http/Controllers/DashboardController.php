<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Vente;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistiques du mois en cours
        $ventesCeMois = Vente::whereMonth('date_vente', Carbon::now()->month)
                             ->whereYear('date_vente', Carbon::now()->year)
                             ->count();

        $chiffreAffairesMois = Vente::whereMonth('date_vente', Carbon::now()->month)
                                    ->whereYear('date_vente', Carbon::now()->year)
                                    ->sum('montant');

        // 2. Top 5 des clients les plus actifs
        $topClients = Client::withCount('ventes')
                            ->orderByDesc('ventes_count')
                            ->take(5)
                            ->get();

        // 3. Alertes stock faible (entre 1 et 5)
        $alertesStock = Produit::where('quantite_stock', '<=', 5)
                               ->where('quantite_stock', '>', 0)
                               ->orderBy('quantite_stock')
                               ->take(5)
                               ->get();

        // 4. TOP 1 PAR CATEGORIE (ta fonctionnalite star)
        $topsParCategorie = Categorie::with(['produits' => function ($query) {
            $query->withSum('ventes', 'quantite')
                  ->orderByDesc('ventes_sum_quantite')
                  ->limit(1);
        }])->get();

        $topsParCategorie = $topsParCategorie->filter(function ($categorie) {
            return $categorie->produits->isNotEmpty()
                && $categorie->produits->first()->ventes_sum_quantite > 0;
        });

        // 5. Graphique : ventes des 6 derniers mois
        $graphiqueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Vente::whereMonth('date_vente', $date->month)
                          ->whereYear('date_vente', $date->year)
                          ->count();

            $graphiqueData[] = [
                'mois' => $date->locale('fr')->isoFormat('MMM'),
                'ventes' => $count
            ];
        }

        $maxVentes = max(array_column($graphiqueData, 'ventes'));
        if ($maxVentes == 0) $maxVentes = 1; // eviter la division par zero

        return view('dashboard.index', compact(
            'ventesCeMois',
            'chiffreAffairesMois',
            'topClients',
            'alertesStock',
            'topsParCategorie',
            'graphiqueData',
            'maxVentes'
        ));
    }
}