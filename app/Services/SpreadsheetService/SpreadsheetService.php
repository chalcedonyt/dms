<?php
namespace App\Services\SpreadsheetService;

use App\Services\SpreadsheetService\SheetHeader;

class SpreadsheetService
{
    protected $client;
    protected $spreadsheetId;
    protected $sheetId;
    protected $currentSheet = null;
    protected $currentHeaders = null;

    //values to ignore in a row
    const STOP_VALUES = ['---'];

    public function __construct(\Google_Service_Sheets $service)
    {
        $this->service = $service;
    }

    public function setSpreadsheetId(string $spreadsheet_id)
    {
        $this->spreadsheetId = $spreadsheet_id;
    }

    public function setSheetId(int $sheet_id)
    {
        $this->sheetId = (int)$sheet_id;
    }

    public function getSheet(bool $use_cache = true): \Google_Service_Sheets_Sheet
    {
        if ($this->currentSheet && $use_cache) {
            return $this->currentSheet;
        }

        $spreadsheet = $this->service->spreadsheets->get($this->spreadsheetId);
        $sheet = collect($spreadsheet->sheets)
        ->first(function ($sheet) {
            return (int)$sheet->properties->sheetId == (int)$this->sheetId;
        });
        $this->currentSheet = $sheet;
        return $sheet;
    }

    public function getRangeString(string $sheet_title, string $range = 'A1:Z10000'): string
    {
        return sprintf("%s!%s", $sheet_title, $range);
    }

    public function getData(string $range)
    {
        $sheet = $this->getSheet();
        return $this->service->spreadsheets_values
        ->get($this->spreadsheetId, $this->getRangeString($sheet->properties->title, $range));
    }

    public function getHeaders(bool $use_cache = true): array
    {
        //get the sheet name
        if ($this->currentHeaders && $use_cache) {
            return $this->currentHeaders;
        }
        $data = $this->getData('A1:Z1')->values[0];
        $this->currentHeaders = [];
        foreach ($data as $h) {
            $header = new SheetHeader($h);
            if ($this->isEmailColumn($header->name))
                $header->setSpecialType(SheetHeader::SPECIAL_EMAIL);
            else if ($this->isNameColumn($header->name))
                $header->setSpecialType(SheetHeader::SPECIAL_NAME);
            else if ($this->isContactColumn($header->name))
                $header->setSpecialType(SheetHeader::SPECIAL_CONTACT);

            $this->currentHeaders[]= $header;
        }
        return $this->currentHeaders;
    }

    public function getItems(): array
    {
        $data = $this->getData('A2:Z10000');
        $member_data = [];
        for($i = 1; $i < count($data->values); $i++) {
            $row = $data->values[$i];
            if ($this->isValidRow($row)) {
                $member_data[]=$row;
            }
        }
        return $member_data;
    }

    public function getTitle(): ?string
    {
        $spreadsheet = $this->service->spreadsheets->get($this->spreadsheetId);
        return sprintf("%s - %s", $spreadsheet->properties->title, $this->getSheet()->properties->title);
    }
    /**
     * Undocumented function
     * @todo Should improve to detect email, name row instead of just the first three.
     * @param [type] $row
     * @param [type] $headers
     * @return boolean
     */
    protected function isValidRow(array $row): bool
    {
        $headers = $this->getHeaders();
        //if the first three columns are not filled, not valid
        $valid = true;
        for($i = 0; $i < count($headers); $i++) {
            if ($i > 2)
                continue;
            else if (!isset($row[$i]) || $row[$i] == '' || in_array($row[$i], self::STOP_VALUES)) {
                $valid = false;
            }
        }
        return $valid;
    }


    protected function isEmailColumn(string $value): bool
    {
        return in_array(trim(strtolower($value)), [
            'email',
            'emails'
        ]);
    }

    protected function isContactColumn(string $value): bool
    {
        return in_array(trim(strtolower($value)), [
            'contact',
            'number',
            'contactno',
            'contact_num',
            'contactnum',
            'contact number'
        ]);
    }

    protected function isNameColumn(string $value): bool
    {
        return in_array(trim(strtolower($value)), [
            'name',
            'fullname',
            'full name'
        ]);
    }
}