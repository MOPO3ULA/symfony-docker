<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

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
     * RegisterUserService constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {

        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function register(User $user)
    {
        //todo: add more logic?
        try {
            $this->entityManager->persist($user);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        } finally {
            $this->entityManager->flush();
        }
    }

}