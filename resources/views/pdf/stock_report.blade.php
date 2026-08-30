<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Rapport d'Activité & Inventaire - PharmaGestion</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            color: #0b1c30; 
            font-size: 11px; 
            line-height: 1.4; 
            margin: 15px; 
        }
        .header { 
            text-align: center; 
            border-bottom: 2px solid #006565; 
            padding-bottom: 8px; 
            margin-bottom: 12px; 
        }
        .title { 
            font-size: 16px; 
            font-weight: bold; 
            color: #006565; 
            margin: 0 0 4px 0; 
            text-transform: uppercase;
        }
        .subtitle { 
            font-size: 11px; 
            color: #555555; 
        }
        .kpi-container { 
            width: 100%; 
            margin-bottom: 14px; 
            border-collapse: collapse;
        }
        .kpi-card { 
            background: #f4f8f8; 
            border: 1px solid #bdc9c8; 
            border-radius: 4px; 
            padding: 8px; 
            text-align: center; 
        }
        .kpi-title { 
            font-size: 9px; 
            text-transform: uppercase; 
            color: #555555; 
            font-weight: bold;
        }
        .kpi-val { 
            font-size: 14px; 
            font-weight: bold; 
            color: #006565; 
            margin-top: 3px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #006565;
            border-bottom: 1px solid #006565;
            padding-bottom: 3px;
            margin-top: 15px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 12px; 
        }
        .table th, .table td { 
            border: 1px solid #d1d5db; 
            padding: 5px 6px; 
            text-align: left; 
            font-size: 10px;
        }
        .table th { 
            background: #006565; 
            color: #ffffff; 
            text-transform: uppercase; 
            font-size: 9px; 
        }
        .badge-ok { color: #006565; font-weight: bold; }
        .badge-low { color: #8b4823; font-weight: bold; }
        .badge-out { color: #ba1a1a; font-weight: bold; }
        .footer { 
            margin-top: 25px; 
            font-size: 9px; 
            text-align: center; 
            color: #6e7979; 
            border-top: 1px solid #bdc9c8; 
            padding-top: 6px; 
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">RAPPORT D'ACTIVITÉ & INVENTAIRE PHARMACEUTIQUE</h1>
        <div class="subtitle">
            <strong>Période couverte : {{ $periodLabel }}</strong> | PharmaGestion - Poste de Santé | Édité le {{ date('d/m/Y à H:i') }}
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <table class="kpi-container">
        <tr>
            <td class="kpi-card" style="width: 20%;">
                <div class="kpi-title">Recette Ventes</div>
                <div class="kpi-val">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</div>
            </td>
            <td class="kpi-card" style="width: 20%;">
                <div class="kpi-title">Nombre de Ventes</div>
                <div class="kpi-val">{{ number_format($totalSalesCount) }} ticket(s)</div>
            </td>
            <td class="kpi-card" style="width: 20%;">
                <div class="kpi-title">Articles Vendus</div>
                <div class="kpi-val">{{ number_format($totalItemsSold) }} unités</div>
            </td>
            <td class="kpi-card" style="width: 20%;">
                <div class="kpi-title">Réassort Reçu</div>
                <div class="kpi-val">+{{ number_format($entriesUnits) }} unités</div>
            </td>
            <td class="kpi-card" style="width: 20%;">
                <div class="kpi-title">Valeur Stock Actuel</div>
                <div class="kpi-val">{{ number_format($stockValue, 0, ',', ' ') }} FCFA</div>
            </td>
        </tr>
    </table>

    <!-- Activité par Vendeuse -->
    @if(count($vendorActivity) > 0)
        <div class="section-title">1. Récapitulatif des Ventes par Vendeuse</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Vendeuse / Caissière</th>
                    <th style="text-align: right;">Nombre de Tickets</th>
                    <th style="text-align: right;">Chiffre d'Affaires Encaissé</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendorActivity as $va)
                    <tr>
                        <td><strong>{{ $va['name'] }}</strong></td>
                        <td style="text-align: right;">{{ number_format($va['sales_count']) }}</td>
                        <td style="text-align: right; font-weight: bold; color: #006565;">{{ number_format($va['sales_revenue'], 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Top Médicaments Vendus -->
    @if(count($topMedications) > 0)
        <div class="section-title">2. Top Médicaments les Plus Vendus sur la Période</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Médicament</th>
                    <th>Dosage</th>
                    <th style="text-align: right;">Quantité Délivrée</th>
                    <th style="text-align: right;">Montant Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topMedications as $top)
                    <tr>
                        <td>{{ $top->code }}</td>
                        <td><strong>{{ $top->name }}</strong></td>
                        <td>{{ $top->dosage }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($top->total_qty) }} unités</td>
                        <td style="text-align: right; font-weight: bold; color: #006565;">{{ number_format($top->total_amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Inventaire Global du Stock -->
    <div class="section-title">3. État Actuel du Stock & Valorisation (Total : {{ $totalMedications }} références / {{ number_format($totalStock) }} unités)</div>
    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Médicament</th>
                <th>Dosage & Forme</th>
                <th>Catégorie</th>
                <th style="text-align: right;">Stock Dispo</th>
                <th style="text-align: right;">Prix Unitaire</th>
                <th style="text-align: right;">Valeur Stock</th>
                <th style="text-align: center;">État</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medications as $med)
                <tr>
                    <td>{{ $med->code }}</td>
                    <td><strong>{{ $med->name }}</strong></td>
                    <td>{{ $med->dosage }} ({{ $med->form }})</td>
                    <td>{{ $med->category->name ?? 'Général' }}</td>
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
        Document d'activité et d'inventaire officiel émis par PharmaGestion à destination du Poste de Santé et du District Médical.
    </div>

</body>
</html>
