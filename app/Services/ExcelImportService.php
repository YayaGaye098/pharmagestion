<?php

namespace App\Services;

use App\Models\Medication;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service central d'import Excel pour les receptions de stock.
 * Reutilise EXACTEMENT la meme logique metier que StockEntryController::store()
 */
class ExcelImportService
{
    // Noms de colonnes acceptes pour chaque champ (insensible a la casse)
    private const COLUMN_ALIASES = [
        'code'            => ['code', 'code medicament', 'cod', 'ref', 'reference'],
        'designation'     => ['designation', 'nom', 'name', 'libelle', 'produit', 'description'],
        'conditionnement' => ['conditionnement', 'uc', 'unite conditionnement', 'packaging', 'unite'],
        'quantite'        => ['quantite', 'qte', 'qty', 'quantites', 'quantite.'],
        'prix_achat'      => ['prix_achat', 'prix achat', 'prix', 'prix unitaire', 'pu', 'cession'],
    ];

    /**
     * Lit et parse un fichier Excel - SANS toucher a la base de donnees.
     */
    public function parseFile(string \): array
    {
        try {
            \ = \PhpOffice\PhpSpreadsheet\IOFactory::load(\);
        } catch (\Exception \) {
            Log::error('ExcelImport: impossible de lire le fichier', ['error' => \->getMessage()]);
            throw new \InvalidArgumentException('Le fichier Excel est invalide ou corrompu.');
        }

        \   = \->getActiveSheet();
        \  = \->getHighestRow();
        \ = \->getHighestColumn();
        \ = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(\);

        // Lire l'en-tete (ligne 1)
        \ = [];
        for (\ = 1; \ <= \; \++) {
            \ = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(\) . '1';
            \   = \->getCell(\)->getValue();
            \[\] = \ !== null ? trim((string)\) : '';
        }

        // Detecter les colonnes
        \       = \->detectColumns(\);
        \ = [];
        foreach (['code', 'quantite', 'prix_achat'] as \) {
            if (!isset(\[\])) { \[] = \; }
        }
        if (!empty(\)) {
            return ['rows' => [], 'columnMap' => \,
                    'errors' => ['missing_columns' => \], 'fatal' => true];
        }

        \ = []; \ = [];
        for (\ = 2; \ <= \; \++) {
            \ = \->readRow(\, \, \, \);
            if (\->isRowEmpty(\)) continue;
            \ = \->parseRow(\, \);
            \[] = \;
            if (!empty(\['errors'])) {
                \['row_errors'][] = ['row' => \, 'code' => \['code'] ?? 'N/A', 'errors' => \['errors']];
            }
        }
        return ['rows' => \, 'columnMap' => \, 'errors' => \, 'fatal' => false];
    }

    /**
     * Enrichit les lignes avec la correspondance medicaments (UNE SEULE requete groupee).
     */
    public function matchMedications(array \): array
    {
        \ = collect(\)->filter(fn(\) => !empty(\['code']) && empty(\['errors']))
            ->pluck('code')->unique()->values()->toArray();

        \ = Medication::whereIn('code', \)->get()->keyBy('code');

        foreach (\ as &\) {
            if (!empty(\['errors'])) { \['match_status'] = 'invalid'; \['medication'] = null; continue; }
            \ = \->get(\['code'] ?? '');
            if (\) {
                \['medication'] = \; \['match_status'] = 'found';
                if (!empty(\['designation'])) {
                    \ = mb_strtolower(trim(\['designation']));
                    \    = mb_strtolower(trim(\->name));
                    if (\ !== \ && similar_text(\, \) < (mb_strlen(\) * 0.6)) {
                        \['name_warning'] = "Designation Excel \\[designation]\ differente de la BDD : \{\->name}\";
                    }
                }
            } else {
                \['medication'] = null; \['match_status'] = 'not_found';
            }
        }
        return \;
    }

    public function referenceAlreadyExists(string \): bool
    {
        if (empty(trim(\))) return false;
        return StockMovement::where('reference_no', strtoupper(trim(\)))->exists();
    }

    /**
     * Confirme l'import - TRANSACTION atomique, meme logique que StockEntryController::store().
     */
    public function confirm(array \, array \, array \, int \, string \, string \): array
    {
        \  = strtoupper(trim(\['reference_no'] ?? '')) ?: null;
        \ = !empty(\['movement_date']) ? Carbon::parse(\['movement_date'])->toDateString() : now()->toDateString();
        \     = trim(\['supplier'] ?? 'District Sanitaire / PNA');

        // Appliquer les overrides manuels
        foreach (\ as \ => \) {
            if (isset(\[\])) {
                \ = Medication::find((int)\);
                if (\) { \[\]['medication'] = \; \[\]['match_status'] = 'found'; }
            }
        }

        \ = array_values(array_filter(\, fn(\) =>
            empty(\['errors']) && \['match_status'] === 'found' && \['medication'] !== null
        ));

        if (empty(\)) throw new \RuntimeException('Aucune ligne valide a importer.');

        \ = 0; \ = 0;

        DB::transaction(function () use (\, \, \, \, \, \, \, &\, &\) {
            foreach (\ as \) {
                \    = Medication::lockForUpdate()->findOrFail(\['medication']->id);
                \      = (int)\['quantite'];
                \ = (float)\['prix_achat'];
                \ = \['conditionnement'] ?? null;

                // === IDENTIQUE A StockEntryController::store() ===
                \->increment('stock_quantity', \);
                \->refresh();

                // Prix de vente : conserver le prix actuel s'il existe, sinon = prix achat
                \ = (float)\->unit_price > 0 ? (float)\->unit_price : \;

                \ = ['status' => \->stockStatusFor(), 'purchase_price' => \, 'unit_price' => \];
                if (\) \['packaging_unit'] = \;
                \->update(\);

                StockMovement::create([
                    'medication_id'     => \->id,
                    'reference_no'      => \,
                    'movement_date'     => \,
                    'supplier'          => \,
                    'packaging_unit'    => \ ?? \->packaging_unit,
                    'type'              => 'entree',
                    'quantity'          => \,
                    'purchase_price'    => \,
                    'selling_price'     => \,
                    'user_id'           => \,
                    'performed_by_name' => \,
                    'notes'             => "Import Excel — fichier : {\}",
                ]);
                // === FIN LOGIQUE IDENTIQUE ===

                \ += \ * \;
                \++;
            }
        });

        Log::info('ExcelImport confirme', ['reference_no' => \, 'lines' => \, 'user_id' => \, 'file' => \]);
        return ['imported_count' => \, 'total_purchase' => \, 'reference_no' => \];
    }

    // ----- Methodes privees -----

    private function detectColumns(array \): array
    {
        \ = [];
        foreach (\ as \ => \) {
            \ = mb_strtolower(trim(\));
            foreach (self::COLUMN_ALIASES as \ => \) {
                if (!isset(\[\]) && in_array(\, \, true)) {
                    \[\] = \;
                }
            }
        }
        return \;
    }

    private function readRow(\, int \, array \, int \): array
    {
        \ = [];
        foreach (\ as \ => \) {
            if (\ > \) { \[\] = null; continue; }
            \ = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(\) . \;
            \  = \->getCell(\);
            if (\ === 'code') {
                // Toujours string pour preserver les zeros initiaux
                \ = (string)\->getFormattedValue();
                \ = (string)\->getValue();
                // Prendre la valeur formatee si elle contient des zeros initiaux
                \ = (str_starts_with(ltrim(\, " "), '0') || strlen(trim(\)) > strlen(trim(\)))
                    ? \ : \;
                \[\] = trim(\);
            } else {
                \[\] = \->getCalculatedValue();
            }
        }
        \['_row'] = \;
        return \;
    }

    private function isRowEmpty(array \): bool
    {
        foreach (\ as \ => \) {
            if (\ === '_row') continue;
            if (\ !== null && trim((string)\) !== '') return false;
        }
        return true;
    }

    private function parseRow(array \, int \): array
    {
        \ = [];
        \   = trim((string)(\['code'] ?? ''));
        if (\ === '') \[] = "Code medicament manquant";
        elseif (mb_strlen(\) > 50) \[] = "Code trop long (max 50 caracteres)";

        \ = \['quantite'] ?? null;
        \ = null;
        if (\ === null || trim((string)\) === '') \[] = "Quantite manquante";
        elseif (!is_numeric(\)) \[] = "Quantite invalide: {\}";
        else { \ = (int)round((float)\); if (\ < 1) \[] = "Quantite doit etre >= 1"; }

        \ = \['prix_achat'] ?? null;
        \ = null;
        if (\ === null || trim((string)\) === '') \[] = "Prix d'achat manquant";
        elseif (!is_numeric(\)) \[] = "Prix d'achat invalide: {\}";
        else { \ = (float)\; if (\ < 0) \[] = "Prix d'achat doit etre >= 0"; }

        return [
            'row'             => \,
            'code'            => \,
            'designation'     => trim((string)(\['designation'] ?? '')),
            'conditionnement' => strtoupper(trim((string)(\['conditionnement'] ?? ''))),
            'quantite'        => \,
            'prix_achat'      => \,
            'montant'         => (\ !== null && \ !== null) ? \ * \ : null,
            'errors'          => \,
            'match_status'    => null,
            'medication'      => null,
            'name_warning'    => null,
        ];
    }
}
