<?php


namespace App\Validate\Constraints;


use App\Validate\Validators\ContainDigitValidator;
use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class ContainDigits extends Constraint
{
    public string $message = 'Должно содержать цифры';

    /**
     * Возвращает класс валидатора.
     *
     * @return string
     */
    public function validatedBy(): string
    {
        return ContainDigitValidator::class;
    }
}
