<?php

namespace App\Validate;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use \Throwable;

class FileValidator
{
    private const maxKBytesFileSize = 2048;

    /**
     * Проверяем правильно ли оканчивается путь и исправляем при надобности
     * @param string $path
     * @return string
     */
    public static function validatePathToSaveFile(string $path): string
    {
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        return $path;
    }

    /**
     * Обезаем все GET параметры
     * @param string $fileUri
     * @return string
     */
    public static function validateFileUri(string $fileUri): string
    {
        return strtok($fileUri, '?');
    }

    /**
     * @param UploadedFile $file
     * @return bool|string
     */
    public static function validateMp3($file)
    {
        try {
            $fileSize = $file->getSize();
        } catch (Throwable $exception) {
            return 'File size must not exceed 2 MB';
        }

        if (!self::validateFileSize($fileSize)) {
            return 'File size must not exceed 2 MB';
        }

        if ($file->getMimeType() !== 'audio/mpeg') {
            return 'Unsupported file format';
        }

        return true;
    }

    /**
     * @param int $size
     * @return bool
     */
    private static function validateFileSize(int $size): bool
    {
        return !(self::bytesToKBytes($size) > self::maxKBytesFileSize);
    }

    /**
     * @param int $bytes
     * @return float
     */
    private static function bytesToKBytes(int $bytes): float
    {
        return ceil($bytes / 1024);
    }
}