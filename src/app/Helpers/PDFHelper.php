<?php


namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PDFHelper
{

    public static function PdfBase64Decoder($pdf_base64)
    {
        $extension = explode('/', $pdf_base64);
        $extension = explode(';', $extension[1]);
        $extension = '.' . $extension[0];
        $separatorFile = explode(',', $pdf_base64);
        $photoEncoded = $separatorFile[1] ?? [];
        $photoDecoded = !empty($photoEncoded) ? base64_decode($photoEncoded) : [];

        $nameFile = Str::random(10) . $extension;

        Storage::put($nameFile, $photoDecoded);

        $document = storage_path('app/') . $nameFile;

        return $document;
    }
}
