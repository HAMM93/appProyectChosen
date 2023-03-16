<?php


namespace App\Helpers;


use App\Exceptions\Storage\Zip\ZipNotCreatedException;
use App\Traits\GeneralValidatesTrait;
use Illuminate\Support\Str;


class ZipHelper
{
    use GeneralValidatesTrait;

    /**
     * @param array $links
     * @return string
     * @throws ZipNotCreatedException
     */
    public static function generateZipByLink(array $links): string
    {
        $zipFile = '/tmp/' . Str::random(10) . '.zip';

        try {
            $zip = new \ZipArchive();

            $zip->open($zipFile, \ZipArchive::CREATE);

            foreach ($links as $link) {
                if (!is_null($link)) {
                    $zip->addFromString(basename($link), file_get_contents($link));
                }
            }

            $zip->close();
        } catch (\Exception $e) {
            throw new ZipNotCreatedException($e);
        }

        return $zipFile;
    }

    /**
     * @param array $links
     * @return string
     * @throws ZipNotCreatedException
     */
    public static function generateZipWithDonorPhotosByLink(array $links): string
    {
        $zipFile = '/tmp/' . Str::random(10) . '.zip';

        try {
            $zip = new \ZipArchive();

            $zip->open($zipFile, \ZipArchive::CREATE);

            foreach ($links as $link) {
                if (!is_null($link)) {
                    $zip->addFromString(sprintf('%s.png', basename($link)), file_get_contents($link));
                }
            }

            $zip->close();
        } catch (\Exception $e) {
            throw new ZipNotCreatedException($e);
        }

        return $zipFile;
    }
}
