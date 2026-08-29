<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Rapport d'Inventaire et Valorisation du Stock</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0b1c30; font-size: 12px; line-height: 1.4; margin: 15px; }
        .header { text-align: center; border-bottom: 2px solid #006565; padding-bottom: 8px; margin-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; color: #006565; margin: 0; }
        .subtitle { font-size: 11px; color: #6e7979; }
        .kpi-container { width: 100%; margin-bottom: 15px; }
        .kpi-card { background: #eff4ff; border: 1px solid #bdc9c8; border-radius: 4px; padding: 10px; text-align: center; }
        .kpi-title { font-size: 10px; text-transform: uppercase; color: #3e4949; }
        .kpi-val { font-size: 16px; font-weight: bold; color: #006565; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #bdc9c8; padding: 6px 8px; text-align: left; }
        .table th { background: #006565; color: #ffffff; text-transform: uppercase; font-size: 10px; }
        .badge-ok { color: #006565; font-weight: bold; }
        .badge-low { color: #8b4823; font-weight: bold; }
        .badge-out { color: #ba1a1a; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #6e7979; border-top: 1px solid #bdc9c8; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">RAPPORT MENSUEL DE STOCK & VALORISATION</h1>
        <div class="subtitle">PharmaGestion - Poste de Santé | Édité le {{ date('d/m/Y à H:i') }}</div>
    </div>

    <table class="kpi-container">
        <tr>
            <td class="kpi-card" style="width: 33%;">
                <div class="kpi-title">Nombre de Références</div>
                <div class="kpi-val">{{ number_format($totalMedications) }}</div>
            </td>
            <td class="kpi-card" style="width: 33%;">
                <div class="kpi-title">Stock Total (Unités)</div>
                <div class="kpi-val">{{ number_format($totalStock) }}</div>
            </td>
            <td class="kpi-card" style="width: 33%;">
                <div class="kpi-title">Valorisation Totale</div>
                <div class="kpi-val">{{ number_format($stockValue, 0, ',', ' ') }} FCFA</div>
            </td>
        </tr>
    </table>

    <h3>Inventaire Détaillé des Produits</h3>

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Médicament</th>
                <th>Dosage & Forme</th>
                <th>Catégorie</th>
                <th style="text-align: right;">Stock</th>
                <th style="text-align: right;">Prix Unitaire</th>
                <th style="text-align: right;">Valeur Totale</th>
                <th style="text-align: center;">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medications as $med)
                <tr>
                    <td>{{ $med->code }}</td>
                    <td><strong>{{ $med->name }}</strong></td>
                    <td>{{ $med->dosage }} ({{ $med->form }})</td>
                    <td>{{ $med->category->name ?? '-' }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($med->stock_quantity) }}</td>
                    <td style="text-align: right;">{{ number_format($med->unit_price, 0, ',', ' ') }} FCFA</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($med->stock_quantity * $med->unit_price, 0, ',', ' ') }} FCFA</td>
                    <td style="text-align: center;">
                        @if($med->stock_quantity <= 0)
                            <span class="badge-out">RUPTURE</span>
                        @elseif($med->stock_quantity <= $med->min_threshold)
                            <span class="badge-low">FAIBLE</span>
                        @else
                            <span class="badge-ok">OK</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document d'inventaire confidentiel à l'attention du District Médical et de la Direction du Poste de Santé.
    </div>

</body>
</html>
