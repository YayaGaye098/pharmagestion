<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Fiche de Comptage Physique d'Inventaire</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0b1c30; font-size: 12px; line-height: 1.4; margin: 15px; }
        .header { text-align: center; border-bottom: 2px solid #006565; padding-bottom: 8px; margin-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; color: #006565; margin: 0; }
        .subtitle { font-size: 11px; color: #6e7979; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #bdc9c8; padding: 8px 10px; text-align: left; }
        .table th { background: #006565; color: #ffffff; text-transform: uppercase; font-size: 10px; }
        .blank-cell { background: #f8f9ff; height: 25px; width: 100px; }
        .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #6e7979; border-top: 1px solid #bdc9c8; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">FICHE DE COMPTAGE D'INVENTAIRE PHYSIQUE</h1>
        <div class="subtitle">Support papier à remplir en rayon | Édité le {{ date('d/m/Y') }}</div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Médicament</th>
                <th>Dosage & Forme</th>
                <th style="text-align: right;">Stock Théorique</th>
                <th style="text-align: center;">Stock Physique Compté</th>
                <th style="text-align: center;">Écart / Observation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medications as $med)
                <tr>
                    <td>{{ $med->code }}</td>
                    <td><strong>{{ $med->name }}</strong></td>
                    <td>{{ $med->dosage }} ({{ $med->form }})</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($med->stock_quantity) }}</td>
                    <td class="blank-cell"></td>
                    <td class="blank-cell"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 40px;">
        <strong>Nom et Signature du Pharmacien Contrôleur :</strong><br/><br/><br/>
        __________________________________________
    </div>

    <div class="footer">
        PharmaGestion V1 - Ce document doit être rempli lors du contrôle physique puis saisi dans l'application web.
    </div>

</body>
</html>
