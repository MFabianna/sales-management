<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $vente->code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #3E2723;
            font-size: 13px;
        }
        table { border-collapse: collapse; }
        .header-table { width: 100%; margin-bottom: 25px; }
        .logo { font-size: 30px; font-weight: bold; color: #8B1E3F; letter-spacing: 4px; }
        .slogan { color: #795548; font-size: 11px; }
        .titre-facture { font-size: 24px; font-weight: bold; color: #8B1E3F; text-align: right; }
        .code-facture { text-align: right; color: #795548; font-size: 12px; }
        .infos-table { width: 100%; margin-bottom: 25px; }
        .infos-table td { padding: 4px; vertical-align: top; }
        .label { color: #8B1E3F; font-weight: bold; }
        .details-table { width: 100%; margin-bottom: 20px; }
        .details-table th {
            background-color: #8B1E3F;
            color: #FFFFFF;
            padding: 10px;
            text-align: left;
        }
        .details-table td { padding: 10px; border-bottom: 1px solid #F8BBD0; }
        .montant-col { text-align: right; }
        .total-table { width: 100%; }
        .total-cell {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #8B1E3F;
            padding: 10px;
            background-color: #FCE4EC;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            color: #795548;
            font-size: 11px;
            border-top: 2px solid #F8BBD0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    {{-- En-tete : logo + numero de facture --}}
    <table class="header-table">
        <tr>
            <td>
                <div class="logo">SALES</div>
                <div class="slogan">Gestion des ventes et clients</div>
            </td>
            <td>
                <div class="titre-facture">FACTURE</div>
                <div class="code-facture">{{ $vente->code }}</div>
            </td>
        </tr>
    </table>

    {{-- Informations client et date --}}
    <table class="infos-table">
        <tr>
            <td style="width: 50%;">
                <span class="label">Client :</span><br>
                {{ $vente->client->prenom }} {{ $vente->client->nom }}<br>
                Contact : {{ $vente->client->contact ?? 'Non renseigne' }}<br>
                Adresse : {{ $vente->client->adresse ?? 'Non renseignee' }}
            </td>
            <td style="width: 50%; text-align: right;">
                <span class="label">Date de vente :</span><br>
                {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    {{-- Detail de la vente --}}
    <table class="details-table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantite</th>
                <th>Prix unitaire</th>
                <th class="montant-col">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $vente->produit->nom }}</td>
                <td>{{ $vente->quantite }} {{ $vente->produit->unite }}</td>
                <td>{{ number_format($vente->produit->prix, 2, ',', ' ') }} Ar</td>
                <td class="montant-col">{{ number_format($vente->montant, 2, ',', ' ') }} Ar</td>
            </tr>
        </tbody>
    </table>

    {{-- Total --}}
    <table class="total-table">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%;" class="total-cell">
                TOTAL : {{ number_format($vente->montant, 2, ',', ' ') }} Ar
            </td>
        </tr>
    </table>

    {{-- Pied de page --}}
    <div class="footer">
        Merci pour votre confiance !<br>
        SALES - Document genere le {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>