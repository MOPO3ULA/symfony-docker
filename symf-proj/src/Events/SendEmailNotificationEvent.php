<?php


namespace App\Events;


use Symfony\Contracts\EventDispatcher\Event;

class SendEmailNotificationEvent extends Event
{
    /**
     * @var string
     */
    private string $userEmail;

    /**
     * SendEmailNotificationEvent constructor.
     * @param string $userEmail
     */
    public function __construct(string $userEmail)
    {
        $this->userEmail = $userEmail;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }
}