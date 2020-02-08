<?php


namespace App\Validate\Constraints;


use App\Validate\Validators\ContainDigitValidator;
use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class ContainDigits extends Constraint
{
    public $message = 'Должно содержать цифры';

    /**
     * Возвращает класс валидатора.
     *
     * @return string
     */
    public function validatedBy()
    {
        return ContainDigitValidator::class;
    }
}
