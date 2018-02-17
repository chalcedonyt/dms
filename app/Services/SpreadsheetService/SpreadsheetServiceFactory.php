<?php
namespace App\Services\SpreadsheetService;

use App\Services\SpreadsheetService\SpreadsheetService;

class SpreadsheetServiceFactory
{
    protected $client;
    protected $spreadsheetId;
    protected $sheetId;
    protected $headers = [];

    public static function create(string $spreadsheet_id, int $sheet_id): SpreadsheetService
    {
        $client = app('GoogleClient');
        $sheet_svc = new \Google_Service_Sheets($client);
        $service = new SpreadsheetService($sheet_svc);
        $service->setSpreadsheetId($spreadsheet_id);
        $service->setSheetId($sheet_id);
        return $service;
    }
}