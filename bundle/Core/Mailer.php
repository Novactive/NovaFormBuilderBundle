<?php
/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Maxim Strukov <m.strukov@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */
declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Core;

use Exception;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Psr\Log\LoggerInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_Mime_SimpleMessage;

/**
 * Class Mailer.
 */
class Mailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $defaultSenderEmail;

    /**
     * Mailer constructor.
     */
    public function __construct(
        Swift_Mailer $mailer,
        LoggerInterface $logger,
        string $defaultSenderEmail
    ) {
        $this->defaultSenderEmail = $defaultSenderEmail;
        $this->mailer             = $mailer;
        $this->logger             = $logger;
    }

    /**
     * @return Swift_Message|mixed
     */
    public function createMessage()
    {
        return $this->mailer->createMessage();
    }

    /**
     * @param array $failedRecipients An array of failures by-reference
     *
     * @return int The number of successful recipients. Can be 0 which indicates failure
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null): int
    {
        $successfulRecipientsNumber = 0;
        try {
            $subject = $message->getSubject();
            if (null !== $subject) {
                $subject = trim(strip_tags($subject));
                $message->setSubject($subject);
                $successfulRecipientsNumber = $this->mailer->send($message, $failedRecipients);
                $this->logger->debug("[Email Sent] {$message->getSubject()} [to] ".json_encode($message->getTo()));
            }
        } catch (Exception $e) {
            $this->logger->error(
                "[Email Sent] {$message->getSubject()} [to] ".json_encode($message->getTo()).' - '.$e->getMessage()
            );
        }

        if (0 === $successfulRecipientsNumber) {
            $this->logger->error(
                "[Email Sent] All Failed. {$message->getSubject()} [to] ".json_encode($message->getTo())
            );
        }

        return $successfulRecipientsNumber;
    }

    public function build(Form $formEntity, string $body): Swift_Mime_SimpleMessage
    {
        $message = $this->createMessage();
        $message->setFrom($formEntity->getSenderEmail() ?? $this->defaultSenderEmail);
        $receivers = [];
        if ($formEntity->isUserSendData()) {
            $receivers = $formEntity->getUserSendEmails();
        }
        if (null !== $formEntity->getReceiverEmail() && $formEntity->isSendData()) {
            $receivers[] = $formEntity->getReceiverEmail();
        }
        $message->setBcc($receivers);
        $message->setSubject("NovaFormBuilder Submission Data from {$formEntity->getName()}");
        $message->setBody($body, 'text/html', 'utf8');

        return $message;
    }
}
