<?php


namespace App\Service\MailServices\Base\Interfaces;


interface IMailer
{
    /**
     * Отправка письма
     * @return mixed
     */
    public function send(string $email);
}