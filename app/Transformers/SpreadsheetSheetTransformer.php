<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class SpreadsheetSheetTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\Google_Service_Sheets_Sheet $sheet)
    {
        return [
            'id' => $sheet->properties->sheetId,
            'name' => $sheet->properties->title,
        ];
    }
}
