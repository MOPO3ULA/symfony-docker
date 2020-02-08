<?php


namespace App\Service\MailServices\Base;


use App\Service\MailServices\Base\Interfaces\IMailer;
use Psr\Log\LoggerInterface;
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
    public function send()
    {
        // TODO: Implement send() method.
    }

    abstract function internalSend();
}