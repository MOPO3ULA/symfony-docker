<?php

namespace App\Service;

use App\Validate\FileValidator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class File
{
    public function saveFile(string $pathToDownload, string $pathToSave, string $filename = ''): string
    {
        if (!$filename) {
            $filename = $this->getFilenameFromUri($pathToDownload);
        }

        $pathToSave = FileValidator::validatePathToSaveFile($pathToSave);

        $client = new Client();
        $resource = fopen($pathToSave . $filename, 'wb');

        $stream = Psr7\stream_for($resource);
        $client->request('GET', $pathToDownload, ['save_to' => $stream]);

        return '/upload/samples/' . $filename;
    }

    private function getFilenameFromUri(string $uri): string
    {
        $arUri = explode('/', $uri);
        $uri = array_pop($arUri);

        return FileValidator::validateFileUri($uri);
    }
}