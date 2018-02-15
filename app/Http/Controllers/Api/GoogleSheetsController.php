<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\DriveFileTransformer;
use App\Transformers\SpreadsheetSheetTransformer;

class GoogleSheetsController extends Controller
{
    protected $spreadsheetSvc;

    protected function getClient() {
        $access_token = \Auth::user()->google_token;
        $client = new \Google_Client();
        $client->setAccessToken($access_token);
        return $client;
    }

    /**
     * Spreadsheet list
     *
     * @return void
     */
    public function index(Request $request)
    {
        $client = $this->getClient();
        $drive_svc = new \Google_Service_Drive($client);
        $results = $drive_svc->files->listFiles([
            'q' => "mimeType='application/vnd.google-apps.spreadsheet'"
        ]);
        $data = fractal()->collection($results->files, new DriveFileTransformer)->toArray();
        return response()->json($data);
    }

    /**
     * Sheets in a spreadsheet
     *
     * @return void
     */
    public function sheets(Request $request, string $spreadsheet_id)
    {
        $client = $this->getClient();
        $sheet_svc = new \Google_Service_Sheets($client);
        $results = $sheet_svc->spreadsheets->get($spreadsheet_id);
        $data = fractal()->collection($results->sheets, new SpreadsheetSheetTransformer)->toArray();
        return response()->json($data);
    }
}
