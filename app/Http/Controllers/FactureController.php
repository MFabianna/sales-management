<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureController extends Controller
{
    // GET /factures : l'inventaire de toutes les factures
    public function index()
    {
        $factures = Vente::with(['client', 'produit'])
            ->latest('date_vente')
            ->paginate(10);

        return view('factures.index', compact('factures'));
    }

    // GET /factures/{vente}/download : generer et telecharger le PDF
    public function download(Vente $vente)
    {
        $vente->load(['client', 'produit']);

        $pdf = Pdf::loadView('factures.pdf', compact('vente'));

        return $pdf->download('facture-' . $vente->code . '.pdf');
    }
}