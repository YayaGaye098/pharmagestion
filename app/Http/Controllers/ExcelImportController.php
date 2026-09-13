<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Services\ExcelImportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Controleur pour l'import Excel des receptions de stock.
 * Les routes sont protegees par middleware role:admin,pharmacist (meme que StockEntryController).
 */
class ExcelImportController extends Controller
{
    public function __construct(private ExcelImportService \)
    {
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ETAPE 3 : Telechargement du modele Excel
    // ─────────────────────────────────────────────────────────────────────────

    public function downloadTemplate()
    {
        // Generer un fichier Excel modele avec PhpSpreadsheet (deja installe via maatwebsite/excel)
        \ = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        \ = \->getActiveSheet();
        \->setTitle('Bordereau de Reception');

        // Entete
        \ = ['code', 'designation', 'conditionnement', 'quantite', 'prix_achat'];
        foreach (\ as \ => \) {
            \ = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(\ + 1);
            \->setCellValue(\ . '1', \);
            // Style gras + fond gris
            \->getStyle(\ . '1')->getFont()->setBold(true);
            \->getStyle(\ . '1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFD9E1F2');
            // Formater colonne code en texte pour preserver les zeros initiaux
            if (\ === 'code') {
                \->getStyle(\ . '2:' . \ . '1000')
                    ->getNumberFormat()->setFormatCode('@');
            }
        }

        // Lignes d'exemple
        \ = [
            ['010640', 'Amoxicilline + Ac. Clavulanique', 'B/50', 50, 3910],
            ['010611', 'Amoxicilline 1G Cp. Secable', 'B/100', 15, 7590],
            ['120340', 'Paracetamol Injectable', 'FL/100', 100, 920],
            ['400340', 'Sodium Chlorure 0.9% Sol Perf', 'FL/500', 150, 690],
        ];
        foreach (\ as \ => \) {
            foreach (\ as \ => \) {
                \ = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(\ + 1);
                \ = \->setCellValue(\ . (\ + 2), \);
                // S'assurer que le code est stocke comme string
                if (\ === 0) {
                    \->getCell(\ . (\ + 2))->setValueExplicit(\, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                }
            }
        }

        // Ajuster la largeur des colonnes
        foreach (range('A', 'E') as \) {
            \->getColumnDimension(\)->setAutoSize(true);
        }

        // Generer le fichier et l'envoyer
        \   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(\);
        \ = 'PharmaGestion_modele_bordereau_' . date('Ymd') . '.xlsx';

        return response()->streamDownload(function () use (\) {
            \->save('php://output');
        }, \, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{\}\"",
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ETAPE 4-7 : Upload + Lecture + Validation + Previsualisation
    // ─────────────────────────────────────────────────────────────────────────

    public function upload(Request \)
    {
        \->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ], [
            'excel_file.required' => 'Veuillez selectionner un fichier Excel.',
            'excel_file.mimes'    => 'Seuls les formats .xlsx et .xls sont acceptes.',
            'excel_file.max'      => 'Le fichier ne doit pas depasser 10 Mo.',
        ]);

        try {
            // Stocker le fichier temporairement
            \     = \->file('excel_file');
            \ = \->getClientOriginalName();
            \  = \->store('excel_imports/tmp', 'local');
            \ = storage_path('app/' . \);

            // Parser le fichier
            \ = \->importService->parseFile(\);

            if (\['fatal'] ?? false) {
                Storage::disk('local')->delete(\);
                \ = implode(', ', \['errors']['missing_columns'] ?? []);
                return redirect()->route('entries.index')
                    ->with('excel_error', "Format de colonnes invalide. Colonnes obligatoires manquantes : {}. Utilisez le modele PharmaGestion.");
            }

            // Enrichir avec la correspondance medicaments (une seule requete)
            \ = \->importService->matchMedications(\['rows']);

            // Stocker les donnees en session pour la page de previsualisation
            session([
                'excel_import_rows'      => \,
                'excel_import_tmp_path'  => \,
                'excel_import_file_name' => \,
                'excel_import_errors'    => \['errors'],
            ]);

            // Charger les medicaments pour les dropdowns "selectionner manuellement"
            \ = Medication::orderBy('name')->get(['id', 'code', 'name', 'dosage']);

            return view('entries.excel-preview', [
                'rows'            => \,
                'allMedications'  => \,
                'parseErrors'     => \['errors'],
                'fileName'        => \,
                'stats'           => \->computeStats(\),
            ]);

        } catch (\InvalidArgumentException \) {
            return redirect()->route('entries.index')
                ->with('excel_error', \->getMessage());
        } catch (\Exception \) {
            Log::error('ExcelImport: erreur upload', ['error' => \->getMessage(), 'trace' => \->getTraceAsString()]);
            return redirect()->route('entries.index')
                ->with('excel_error', 'Une erreur inattendue est survenue lors de la lecture du fichier Excel.');
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ETAPE 8-14 : Confirmation + Transaction + Stock + Marges + Tracabilite
    // ─────────────────────────────────────────────────────────────────────────

    public function confirm(Request \)
    {
        \->validate([
            'reference_no'  => ['nullable', 'string', 'max:100'],
            'movement_date' => ['required', 'date'],
            'supplier'      => ['nullable', 'string', 'max:255'],
            'overrides'     => ['nullable', 'array'],
            'overrides.*'   => ['nullable', 'integer', 'exists:medications,id'],
        ], [
            'movement_date.required' => "La date de reception est obligatoire.",
        ]);

        // Recuperer les donnees de session
        \ = session('excel_import_rows');
        if (empty(\)) {
            return redirect()->route('entries.index')
                ->with('excel_error', 'Session expiree. Veuillez reimporter le fichier Excel.');
        }

        try {
            // ETAPE 14 : Verification doublon
            \ = strtoupper(trim(\->input('reference_no', '')));
            if (!empty(\) && \->importService->referenceAlreadyExists(\)) {
                return back()->with('excel_warning', "Une reception portant la reference {} existe deja dans la base. Verifiez avant de confirmer.");
            }

            \ = \->input('overrides', []);
            \ = [
                'reference_no'  => \,
                'movement_date' => \->input('movement_date'),
                'supplier'      => \->input('supplier', 'District Sanitaire / PNA'),
            ];

            // ETAPE 10-13 : Import transactionnel
            \ = \->importService->confirm(
                \,
                \,
                \,
                auth()->id(),
                auth()->user()->name ?? 'Administrateur',
                session('excel_import_file_name', 'inconnu.xlsx')
            );

            // Nettoyer la session et le fichier temporaire
            \ = session('excel_import_tmp_path');
            if (\) {
                \Storage::disk('local')->delete(\);
            }
            session()->forget(['excel_import_rows', 'excel_import_tmp_path', 'excel_import_file_name', 'excel_import_errors']);

            // Afficher la page de succes
            return view('entries.excel-success', [
                'importedCount' => \['imported_count'],
                'totalPurchase' => \['total_purchase'],
                'referenceNo'   => \['reference_no'],
                'movementDate'  => \->input('movement_date'),
                'supplier'      => \['supplier'],
            ]);

        } catch (\RuntimeException \) {
            Log::warning('ExcelImport: confirm echec', ['error' => \->getMessage()]);
            return back()->with('excel_error', \->getMessage());
        } catch (\Exception \) {
            Log::error('ExcelImport: transaction echec', ['error' => \->getMessage(), 'trace' => \->getTraceAsString()]);
            return back()->with('excel_error', 'Impossible d importerla reception. Aucune modification du stock n\'a ete effectuee. Consultez les logs pour plus de details.');
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Methodes privees
    // ─────────────────────────────────────────────────────────────────────────

    private function computeStats(array \): array
    {
        \        = count(\);
        \        = 0;
        \     = 0;
        \      = 0;
        \ = 0;

        foreach (\ as \) {
            if (!empty(\['errors'])) { \++; continue; }
            if (\['match_status'] === 'found') { \++; \ += (float)(\['montant'] ?? 0); }
            else { \++; }
        }

        return [
            'total'         => \,
            'found'         => \,
            'not_found'     => \,
            'invalid'       => \,
            'total_purchase'=> \,
        ];
    }
}
