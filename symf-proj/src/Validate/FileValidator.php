<?php

namespace App\Validate;


class FileValidator
{
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
}