<?php


namespace App\Service\MailServices\Base;


use App\Service\MailServices\Base\Interfaces\IMailer;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
     * MailerService constructor.
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     */
    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function send(string $email)
    {
        //todo: more logic/logging/validating/change functions arguments?
        //todo: add logging in database?
        try {
            $this->internalSend($email);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['class:' => get_called_class(), 'line:' => $exception->getLine()]);
        }
    }

    /**
     *
     * @param string $email
     * @return mixed
     */
    protected abstract function internalSend(string $email);
}