<?php


namespace App\Service;


use App\Entity\User;
use App\Events\SendEmailNotificationEvent;
use App\Utils\Util;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RegisterUserService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    /**
     * RegisterUserService constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    public function register(User $user)
    {
        try {
            $this->entityManager->persist($user);
            $this->dispatcher->dispatch(new SendEmailNotificationEvent($user->getEmail()));
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        } finally {
            $this->entityManager->flush();
        }
    }

}