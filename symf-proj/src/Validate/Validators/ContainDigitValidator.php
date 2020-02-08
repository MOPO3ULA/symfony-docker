<?php


namespace App\Validate\Validators;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class ContainDigitValidator extends ConstraintValidator
{
    /**
     * Валидация.
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/[\d]+/', $value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
