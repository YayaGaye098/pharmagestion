<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Caisse - {{ $sale->reference }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
        }
        body {
            font-family: 'Courier New', Courier, monospace, 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 8px 12px;
            width: 76mm;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }
        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 2px 0;
            text-transform: uppercase;
        }
        .header p {
            font-size: 9px;
            margin: 1px 0;
        }
        .info-block {
            margin-bottom: 6px;
            font-size: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 4px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 6px;
        }
        th {
            border-bottom: 1px solid #000;
            text-align: left;
            padding: 3px 0;
            font-size: 9px;
            text-transform: uppercase;
        }
        td {
            padding: 3px 0;
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .totals-block {
            border-top: 1px dashed #000;
            border-bottom: 1px solid #000;
            padding: 5px 0;
            margin-bottom: 8px;
            font-size: 11px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .grand-total {
            font-size: 13px;
            font-weight: bold;
            margin-top: 3px;
            padding-top: 3px;
            border-top: 1px solid #000;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            margin-top: 8px;
            padding-top: 4px;
            line-height: 1.3;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PHARMAGESTION</h1>
        <p>Poste de Santé & Dispensaire</p>
        <p>Tél: +221 33 000 00 00 / +221 77 000 00 00</p>
        <p>Sénégal</p>
    </div>

    <div class="info-block">
        <div class="info-row">
            <span>Ticket N° :</span>
            <span class="bold">{{ $sale->reference }}</span>
        </div>
        <div class="info-row">
            <span>Date :</span>
            <span>{{ $sale->created_at->format('d/m/Y H:i:s') }}</span>
        </div>
        <div class="info-row">
            <span>Vendeuse :</span>
            <span>{{ $sale->user->name ?? 'Caissière' }}</span>
        </div>
        @if($sale->patient_name)
        <div class="info-row">
            <span>Patient :</span>
            <span class="bold">{{ $sale->patient_name }}</span>
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50%;">Article</th>
                <th style="width: 15%;" class="text-center">Qté</th>
                <th style="width: 35%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>
                        <div class="bold">{{ $item->medication->name ?? 'Article' }}</div>
                        <div style="font-size: 8px; color: #444;">{{ $item->medication->dosage ?? '' }}</div>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->subtotal, 0, ',', ' ') }} F</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-block">
        <div class="total-row grand-total">
            <span>TOTAL À PAYER :</span>
            <span>{{ number_format($sale->total_amount, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="total-row" style="font-size: 9px; margin-top: 4px;">
            <span>Règlement ({{ ucfirst($sale->payment_method) }}) :</span>
            <span>{{ number_format($sale->paid_amount, 0, ',', ' ') }} FCFA</span>
        </div>
        @if($sale->change_amount > 0)
        <div class="total-row bold" style="font-size: 10px;">
            <span>MONNAIE RENDUE :</span>
            <span>{{ number_format($sale->change_amount, 0, ',', ' ') }} FCFA</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p class="bold">Merci pour votre visite !</p>
        <p>Les médicaments vendus ne sont ni repris ni échangés.</p>
        <p style="margin-top: 4px; font-size: 8px; font-family: monospace;">{{ $sale->reference }}</p>
    </div>
</body>
</html>
