<?php


namespace App\Service\MailServices;


use App\Repository\UserRepository;
use App\Service\MailServices\Base\MailerService;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class RegistrationMailService extends MailerService
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    private $user;

    public function __construct(LoggerInterface $logger, TranslatorInterface $translator, UserRepository $repository, Environment $twig)
    {

        $this->logger = $logger;
        $this->translator = $translator;
        parent::__construct($this->logger, $this->translator);

        $twig->render(
            'templates/emails/registration/registration.html.twig'
        );

        $this->repository = $repository;
    }

    /**
     * Send registration email
     * @param string $email
     */
    protected function internalSend(string $email)
    {
        $this->user = $this->repository->findOneBy(['email' => $email]);
    }
}