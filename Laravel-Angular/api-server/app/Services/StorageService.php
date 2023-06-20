<?php
declare(strict_types=1);

namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;

class StorageService
{
    public function storeAudio(UploadedFile $file, ?string $albumFolderId = null): string
    {
        $fileName = uniqid(more_entropy: true);
        if ($albumFolderId === null) {
            $filePath = "audio/{$fileName}.mp3";
        } else {
            $filePath = "audio/{$albumFolderId}/{$fileName}.mp3";
        }

        $this->getClient()->putObject(
            [
                'Bucket' => 'audio',
                'Key' => $filePath,
                'ACL' => 'public-read',
                'Body' => $file->getContent(),
            ]
        );

        $this->getClient()->waitUntil('ObjectExists', ['Bucket' => 'audio', 'Key' => $filePath]);

        return $filePath;
    }

    private function getClient(): S3Client
    {
        return new S3Client(config('aws'));
    }

    public function removeAudio(string $audioPath): bool
    {
        $client = $this->getClient();
        $result = $client->deleteObject([
            'Bucket' => 'audio',
            'Key' => $audioPath
        ]);

        return $result['DeleteMarker'];
    }

}
