<?php


namespace App\Service\MailServices;


use App\Repository\UserRepository;
use App\Service\MailServices\Base\MailerService;
use Psr\Log\LoggerInterface;
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
        parent::__construct($logger, $translator, $mailer);

        $this->logger = $logger;
        $this->translator = $translator;
        $this->repository = $repository;
        $this->twig = $twig;
    }

    /**
     * Send registration email
     * @param string $email
     * @return Email
     */
    protected function internalSend(string $email)
    {
        $this->user = $this->repository->findOneBy(['email' => $email]);

        try {
            $html = $this->twig->render(
                '@TwigTemplate/emails/registration/registration.html.twig',
                [
                    'main' => $this->translator->trans('register.mail.main', ['%username%' => $this->user->getUsername()]),
                    'description' => $this->translator->trans('register.mail.description'),
                    'link_description' => $this->translator->trans('register.mail.link_description'),
                    'link' => $this->translator->trans('register.mail.link'),
                    'footer' => $this->translator->trans('register.mail.footer'),
                    'footer_end' => $this->translator->trans('register.mail.footer_end'),
                ]
            );
        } catch (LoaderError $e) {
            $this->logger->error($e->getMessage());
        } catch (SyntaxError $e) {
            $this->logger->error($e->getMessage());
        } catch (RuntimeError $e) {
            $this->logger->error($e->getMessage());
        }

        $emailObject = (new Email())
            ->from('hello@example.com')
            ->to($this->user->getEmail())
            ->subject($this->translator->trans('register.mail.subject'))
            ->text($this->translator->trans('register.mail.text'))
            ->html($html);

        return $emailObject;
    }
}