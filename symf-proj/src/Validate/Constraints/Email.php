<?php


namespace App\Validate\Constraints;

use Symfony\Component\Validator\Constraints\Email as SymfonyEmail;
use Symfony\Component\Validator\Constraints\EmailValidator;

/**
 * @Annotation
 */
class Email extends SymfonyEmail
{
    public $message = 'Укажите правильный E-mail';

    /**
     * Возвращает класс валидатора
     *
     * @return string
     */
    public function validatedBy()
    {
        return EmailValidator::class;
    }
}
