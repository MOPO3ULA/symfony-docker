<?php


namespace App\Service\MailServices;


use App\Repository\UserRepository;
use App\Service\MailServices\Base\MailerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RegistrationMailService extends MailerService
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    private $user;
    /**
     * @var Environment
     */
    private Environment $twig;
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * RegistrationMailService constructor.
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     * @param UserRepository $repository
     * @param Environment $twig
     * @param MailerInterface $mailer
     */
    public function __construct(
        LoggerInterface $logger,
        TranslatorInterface $translator,
        UserRepository $repository,
        Environment $twig,
        MailerInterface $mailer)
    {
        parent::__construct($logger, $translator);

        $this->logger = $logger;
        $this->translator = $translator;
        $this->repository = $repository;
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * Send registration email
     * @param string $email
     */
    protected function internalSend(string $email)
    {
        $this->user = $this->repository->findOneBy(['email' => $email]);

        try {
            $html = $this->twig->render(
                '@TwigTemplate/emails/registration/registration.html.twig'
            );
        } catch (LoaderError $e) {
            $this->logger->error($e->getMessage());
        } catch (SyntaxError $e) {
            $this->logger->error($e->getMessage());
        } catch (RuntimeError $e) {
            $this->logger->error($e->getMessage());
        }

        $email = (new Email())
            ->from('hello@example.com')
            ->to($this->user->getEmail())
            ->subject($this->translator->trans('register.mail.subject'))
            ->text($this->translator->trans('register.mail.text'))
            ->html($html);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage(), ['Trace' => $exception->getTraceAsString()]);
        }


    }
}