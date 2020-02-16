<?php


namespace App\EventListeners\Registration;


use App\Events\SendEmailNotificationEvent;
use App\Service\MailServices\RegistrationMailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegistrationSubscriber implements EventSubscriberInterface
{
    /**
     * @var RegistrationMailService
     */
    private RegistrationMailService $mailService;

    /**
     * UserRegistrationSubscriber constructor.
     * @param RegistrationMailService $mailService
     */
    public function __construct(RegistrationMailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return
            [
                SendEmailNotificationEvent::class => 'onRegisteredNewUserOnSite'
            ];
    }

    /**
     *Событие регистрации нового юзера
     * @param SendEmailNotificationEvent $event
     */
    public function onRegisteredNewUserOnSite(SendEmailNotificationEvent $event)
    {
        if ($event->getUserEmail()) {
            $this->mailService->send($event->getUserEmail());
        }
    }
}