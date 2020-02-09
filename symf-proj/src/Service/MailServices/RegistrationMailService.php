<?php


namespace App\Service\MailServices;


use App\Repository\UserRepository;
use App\Service\MailServices\Base\MailerService;
use Psr\Log\LoggerInterface;
use stringEncode\Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationMailService extends MailerService
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    public function __construct(LoggerInterface $logger, TranslatorInterface $translator, UserRepository $repository)
    {

        $this->logger = $logger;
        $this->translator = $translator;
        parent::__construct($this->logger, $this->translator);

        $this->repository = $repository;
    }

    /**
     * Send registration email
     * @param string $email
     * @throws Exception
     */
    protected function internalSend(string $email)
    {

    }
}