@extends('layouts.sales')

@section('titre', 'Nouvelle vente')

@section('content')
    <h1 class="section-title">Enregistrer une nouvelle vente</h1>

    <div class="card p-4" style="max-width: 800px;">
        <form method="POST" action="{{ route('ventes.store') }}" id="formVente">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de la vente *</label>
                    <input type="date" name="date_vente" value="{{ old('date_vente', date('Y-m-d')) }}" required
                           class="form-control @error('date_vente') is-invalid @enderror">
                    @error('date_vente') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Client *</label>
                    <select name="client_id" required class="form-select @error('client_id') is-invalid @enderror">
                        <option value="">Choisir un client...</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->nom }} {{ $client->prenom }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Produit *</label>
                <select name="produit_id" id="selectProduit" required class="form-select @error('produit_id') is-invalid @enderror">
                    <option value="">Choisir un produit...</option>
                    @foreach($produits as $produit)
                        <option value="{{ $produit->id }}" 
                                data-prix="{{ $produit->prix }}" 
                                data-stock="{{ $produit->quantite_stock }}"
                                data-unite="{{ $produit->unite }}"
                                {{ old('produit_id') == $produit->id ? 'selected' : '' }}>
                            {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }} {{ $produit->unite }})
                        </option>
                    @endforeach
                </select>
                @error('produit_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Quantite *</label>
                <div class="input-group">
                    <input type="number" step="0.01" min="0.01" name="quantite" id="inputQuantite" 
                        value="{{ old('quantite') }}" required
                        class="form-control @error('quantite') is-invalid @enderror">
                    <span class="input-group-text" id="spanUnite">-</span>
                </div>
                <small class="text-muted" id="infoStock">Selectionnez un produit pour voir le stock disponible.</small>
                <div class="text-danger small fw-bold d-none" id="erreurStock">
                    La quantite depasse le stock disponible !
                </div>
                @error('quantite') 
                    <div class="text-danger small fw-bold">{{ $message }}</div> 
                @enderror
            </div>
                    @error('quantite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Montant Total</label>
                    <input type="text" id="inputMontant" class="form-control bg-light fw-bold text-danger" readonly value="0.00 Ar">
                    <small class="text-muted">Calcule automatiquement (Quantite x Prix unitaire)</small>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-rose">Enregistrer la vente</button>
                <a href="{{ route('ventes.index') }}" class="btn btn-outline-rose">Annuler</a>
            </div>
        </form>
    </div>

    {{-- Script pour le calcul automatique --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectProduit = document.getElementById('selectProduit');
        const inputQuantite = document.getElementById('inputQuantite');
        const inputMontant = document.getElementById('inputMontant');
        const spanUnite = document.getElementById('spanUnite');
        const infoStock = document.getElementById('infoStock');
        const erreurStock = document.getElementById('erreurStock');

        function calculerMontant() {
            const selectedOption = selectProduit.options[selectProduit.selectedIndex];

            // Si aucun produit n'est selectionne
            if (!selectedOption || !selectedOption.value) {
                spanUnite.textContent = '-';
                inputMontant.value = '0.00 Ar';
                infoStock.textContent = 'Selectionnez un produit pour voir le stock disponible.';
                inputQuantite.removeAttribute('max');
                erreurStock.classList.add('d-none');
                return;
            }

            const prix = parseFloat(selectedOption.getAttribute('data-prix')) || 0;
            const stock = parseFloat(selectedOption.getAttribute('data-stock')) || 0;
            const quantite = parseFloat(inputQuantite.value) || 0;
            const unite = selectedOption.getAttribute('data-unite') || '-';

            spanUnite.textContent = unite;

            // On limite la quantite au stock disponible
            inputQuantite.max = stock;
            infoStock.textContent = 'Stock disponible : ' + stock + ' ' + unite;

            // Verification en temps reel
            if (quantite > stock) {
                erreurStock.classList.remove('d-none');
                inputQuantite.classList.add('is-invalid');
            } else {
                erreurStock.classList.add('d-none');
                inputQuantite.classList.remove('is-invalid');
            }

            const total = prix * quantite;
            inputMontant.value = total.toFixed(2) + ' Ar';
        }

        selectProduit.addEventListener('change', calculerMontant);
        inputQuantite.addEventListener('input', calculerMontant);

        // Calcul initial au chargement de la page
        calculerMontant();
    });
</script>
@endsection