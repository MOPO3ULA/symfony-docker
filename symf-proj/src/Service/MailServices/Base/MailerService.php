<?php


namespace App\Service\MailServices\Base;


use App\Service\MailServices\Base\Interfaces\IMailer;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class MailerService implements IMailer
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;
    /**
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     *
     * @param string $email
     * @return mixed
     */
    protected abstract function internalSend(string $email);

    /**
     * MailerService constructor.
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     * @param MailerInterface $mailer
     */
    public function __construct(LoggerInterface $logger, TranslatorInterface $translator, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->translator = $translator;
        $this->mailer = $mailer;
    }

    /**
     * @inheritDoc
     */
    public function send(string $email)
    {
        //todo: more logic/logging/validating/change functions arguments?
        //todo: add logging in database?
        try {
            $email = $this->internalSend($email);
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage(), ['class:' => get_called_class(), 'line:' => $exception->getLine()]);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['class:' => get_called_class(), 'line:' => $exception->getLine()]);
        }
    }

}