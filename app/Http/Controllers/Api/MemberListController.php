<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Transformers\DriveFileTransformer;
use App\Transformers\SpreadsheetSheetTransformer;

use App\Services\SpreadsheetService\SpreadsheetServiceFactory;

class GoogleSheetsController extends Controller
{
    protected $spreadsheetSvc;

    public function show(Request $request, string $spreadsheet_id, int $sheet_id)
    {
        $service = SpreadsheetServiceFactory::create($spreadsheet_id, $sheet_id);
        return response()->json([
            'suggested_title' => $service->getTitle(),
            'headers' => collect($service->getHeaders())->map(function ($header) {
                return $header->toArray();
            }),
            'items' => $service->getItems()
        ]);
    }
    /**
     * Spreadsheet list
     *
     * @return void
     */
    public function index(Request $request)
    {
        $client = app('GoogleClient');
        $drive_svc = new \Google_Service_Drive($client);
        $results = $drive_svc->files->listFiles([
            'q' => "mimeType='application/vnd.google-apps.spreadsheet'",
            'pageSize' => 25
        ]);
        $data = fractal()->collection($results->files, new DriveFileTransformer, 'spreadsheets')->toArray();
        return response()->json($data);
    }

    /**
     * Sheets in a spreadsheet
     *
     * @return void
     */
    public function sheets(Request $request, string $spreadsheet_id)
    {
        $client = app('GoogleClient');
        $sheet_svc = new \Google_Service_Sheets($client);
        $results = $sheet_svc->spreadsheets->get($spreadsheet_id);
        $data = fractal()->collection($results->sheets, new SpreadsheetSheetTransformer, 'sheets')->toArray();
        return response()->json($data);
    }

    public function sheetPreview(Request $request, string $spreadsheet_id, int $sheet_id)
    {
        $service = SpreadsheetServiceFactory::create($spreadsheet_id, $sheet_id);

        $data = [
            'headers' => $service->getHeaders(),
            'body' => $service->getBody(),
        ];
        return response()->json($data);
    }
}
