<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class DriveFileTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\Google_Service_Drive_DriveFile $file)
    {
        return [
            'id' => $file->id,
            'name' => $file->name,
            'mime_type' => $file->mimeType
        ];
    }
}
