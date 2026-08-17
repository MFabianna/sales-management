<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    // Affiche la page d'accueil (pour l'instant simple,
    // les statistiques arriveront a la Phase 10)
    public function index()
    {
        return view('dashboard.index');
    }
}