<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Bon de Délivrance #{{ $movement->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0b1c30; font-size: 13px; line-height: 1.5; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #006565; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; color: #006565; margin: 0; }
        .subtitle { font-size: 12px; color: #6e7979; margin-top: 5px; }
        .box { border: 1px solid #bdc9c8; border-radius: 6px; padding: 15px; background: #f8f9ff; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #bdc9c8; padding: 8px 12px; text-align: left; }
        .table th { background: #006565; color: #ffffff; text-transform: uppercase; font-size: 11px; }
        .footer { margin-top: 40px; font-size: 11px; text-align: justify; color: #6e7979; border-top: 1px solid #bdc9c8; padding-top: 10px; }
        .signatures { margin-top: 50px; width: 100%; }
        .signatures td { width: 50%; vertical-align: top; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">PharmaGestion - Bon de Délivrance</h1>
        <div class="subtitle">Poste de Santé - Réseau Médical du Sénégal</div>
    </div>

    <div class="box">
        <strong>N° de Reçu :</strong> #REC-{{ str_pad($movement->id, 5, '0', STR_PAD_LEFT) }}<br/>
        <strong>Date & Heure :</strong> {{ $movement->created_at->format('d/m/Y à H:i') }}<br/>
        <strong>Agent Responsable :</strong> {{ $movement->performed_by_name ?? 'Pharmacien' }}<br/>
        <strong>Bénéficiaire / Service :</strong> {{ $movement->notes ?? 'Prescription médicale' }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Médicament</th>
                <th>Dosage & Forme</th>
                <th style="text-align: right;">Quantité</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $movement->medication->code ?? '-' }}</td>
                <td><strong>{{ $movement->medication->name ?? 'Médicament' }}</strong></td>
                <td>{{ $movement->medication->dosage ?? '' }} ({{ $movement->medication->form ?? '' }})</td>
                <td style="text-align: right; font-weight: bold; color: #ba1a1a;">-{{ $movement->quantity }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <strong>Signature du Pharmacien / Agent :</strong><br/><br/><br/>
                ___________________________
            </td>
            <td style="text-align: right;">
                <strong>Signature du Bénéficiaire :</strong><br/><br/><br/>
                ___________________________
            </td>
        </tr>
    </table>

    <div class="footer">
        Document généré automatiquement par PharmaGestion V1. Ce bon atteste de la sortie physique des médicaments de la réserve du poste de santé.
    </div>

</body>
</html>
