<?php

namespace App\AppRinger;

use Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Exception;

class ImageUtils
{
    public static function uploadImage($file, $folder, $filename)
    {
        $filePath = $folder;

        $file_path = Storage::disk('public_uploads')->putFileAs($filePath, $file, $filename);
        return $file_path;
    }

    public static function uploadImageOnS3($file, $key)
    {
        $s3 = new S3Client([
            'region'  => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);

        try {
            $s3->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $key,
                'SourceFile'   => $file,
            ]);

            return \App\Helpers\SiteHelper::getObjectUrl($key);
        } catch (Aws\S3\Exception\S3Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
