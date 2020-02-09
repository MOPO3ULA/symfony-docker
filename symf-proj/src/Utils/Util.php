<?php

namespace App\Utils;


class Util
{
    /**
     * @param mixed $message
     * @param string $file
     * @param bool $backtrace
     */
    public static function log($message, $file = '', $backtrace = false): void
    {
        if (!$file) {
            $file = 'log.txt';
        }
        $file = $_SERVER['DOCUMENT_ROOT'] . '/' . $file;
        $text = date('Y-m-d H:i:s') . ' ';

        if (is_array($message)) {
            $text .= print_r($message, true);
        } else {
            $text .= $message;
        }

        $text .= "\n";
        if ($backtrace) {
            $backtrace = reset(debug_backtrace());
            $text = 'Called in file: ' . $backtrace['file'] . ' in line: ' . $backtrace['line'] . " \n" . $text;
        }
        if ($fh = fopen($file, 'ab')) {
            fwrite($fh, $text);
            fclose($fh);
        }
    }

    public static function strToLowerCamelCase(string $str): string
    {
        $str = ucwords($str);

        return lcfirst($str);
    }

    public static function strToUpperCamelCase(string $str): string
    {
        return ucwords($str);
    }
}