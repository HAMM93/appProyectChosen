<?php


namespace App\Services\Storage;

use App\Exceptions\Service\AWSService\S3Bucket\AWSS3DeletingObjectException;
use App\Exceptions\Service\AWSService\S3Bucket\AWSS3UploadingObjectException;
use App\Helpers\PDFHelper;
use App\Helpers\ImageHelper;
use Aws\Result;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Support\Str;

class AWSS3Service
{
    private $versionId;
    private $effectiveUri;

    /**
     * @param string $base64
     * @param string $folder
     * @throws \Exception
     */
    public function putImageFromBase64(string $base64, string $folder): void
    {
        //TODO :: Change solution to external service.
//        $filename = ImageHelper::validateImageBase64($base64);

        $this->putObject($base64, $folder);
    }

    /**
     * @throws \Exception
     */
    public function putDocumentFromBase64(string $base64, string $folder): void
    {
        $filename = PDFHelper::PdfBase64Decoder($base64);

        $this->putObject($filename, $folder);
    }

    /**
     * @param string $filename
     * @param string $folder
     * @throws \Exception
     */
    public function putObject(string $filename, string $folder): void
    {
        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('MY_AWS_STORAGE_REGION'),
            'credentials' => [
                'key' => env('MY_AWS_STORAGE_KEY'),
                'secret' => env('MY_AWS_STORAGE_SECRET')
            ]]);

        $name = 'image/' . $folder . '/' . Str::uuid();

        try {
            $result = $s3->putObject([
                'Bucket' => env('MY_AWS_BUCKET'),
                'Key' => $name,
                'SourceFile' => $filename,
                'ACL' => 'public-read'
            ]);

            //TODO :: (Joel) Needs to implement a "Storage class" to handle all operations with files
            $this->versionId = $result['VersionId'];
            $this->effectiveUri = $result['ObjectURL'];

//            unlink($filename);
        } catch (S3Exception $e) {
            throw new AWSS3UploadingObjectException($e);
        }
    }

    /**
     * @param string $bucket
     * @return Result
     */
    public function getObjects(string $bucket): Result
    {
        $s3 = new S3Client(['version' => 'latest', 'region' => env('MY_AWS_STORAGE_REGION'), 'credentials' => [
            'key' => env('MY_AWS_STORAGE_KEY'),
            'secret' => env('MY_AWS_STORAGE_SECRET')
        ]]);

        $result = $s3->listObjects([
            'Bucket' => $bucket
        ]);

        return $result;
    }

    /**
     * @param string $bucket
     * @param string $objectName
     * @return string
     * @throws \Exception
     */
    public function deleteObject(string $bucket, string $objectName): string
    {
        $s3 = new S3Client(['version' => 'latest', 'region' => env('MY_AWS_STORAGE_REGION'), 'credentials' => [
            'key' => env('MY_AWS_STORAGE_KEY'),
            'secret' => env('MY_AWS_STORAGE_SECRET')
        ]]);

        try {
            $s3->deleteObject([
                'Bucket' => $bucket,
                'Key' => $objectName
            ]);
            return trans('general.deleted');
        } catch (\Exception $e) {
            throw new AWSS3DeletingObjectException($e);
        }
    }

    /**
     * @return string
     */
    public function getUriFile(): string
    {
        return $this->effectiveUri;
    }

    /**
     * @return string
     */
    public function getVersionId(): string
    {
        return $this->versionId;
    }
}
