<?php

namespace App\Validate;


class CompetitionValidator
{
    /**
     * @param string $countOfSamplesString
     * @return int
     */
    public static function getCountOfSamplesFromString(string $countOfSamplesString): int
    {
        $arStrSamples = explode(' of ', $countOfSamplesString);
        return (int) array_pop($arStrSamples);
    }

    public static function validateString(string $str): string
    {
        return trim($str);
    }

    public static function validateDescription(string $description): string
    {
        return self::validateString(str_replace('Description : ', '', $description));
    }

    public static function validateLength(string $textLength): string
    {
        return self::validateString(str_replace('/', '', $textLength));
    }
}