<?php


namespace App\Events;


use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class SendEmailNotificationEvent extends Event
{
    /**
     * @var User
     */
    private User $user;

    /**
     * SendEmailNotificationEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserDomain() : User
    {
        return $this->user;
    }
}