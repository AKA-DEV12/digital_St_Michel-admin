<?php

namespace App\Services;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportService
{
    /**
     * Handle the export logic for both CSV and PDF formats globally.
     *
     * @param Request $request
     * @param string $title Title for the PDF export
     * @param string $filenameBase Base name for the downloaded file (without extension)
     * @param array $columns Array of column headers
     * @param \Illuminate\Support\Collection $data Collection of data to export
     * @param callable $mapCallback Closure that takes an item and returns an array of row data
     */
    public function export(Request $request, string $title, string $filenameBase, array $columns, $data, callable $mapCallback)
    {
        $rows = [];
        foreach ($data as $item) {
            $rows[] = $mapCallback($item);
        }

        $format = $request->get('format', 'csv');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.exports.generic_pdf', [
                'title' => $title,
                'columns' => $columns,
                'rows' => $rows
            ])->setPaper('a4', 'landscape');

            return $pdf->download($filenameBase . ".pdf");
        }

        // Default CSV Export
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filenameBase}.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($rows, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for proper UTF-8 handling in Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
