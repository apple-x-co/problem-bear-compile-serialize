<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TemplateNotFoundException;
use AppCore\Domain\Mail\TemplateRendererInterface;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Qualifier\EmailDir;
use PHPMailer\PHPMailer\PHPMailer;

use function is_readable;

use const DIRECTORY_SEPARATOR;

class SmtpMail implements TransportInterface
{
    public function __construct(
        #[EmailDir]
        private string $emailDir,
        private readonly PHPMailer $mailer,
        private readonly TemplateRendererInterface $templateRenderer,
    ) {
    }

    public function send(Email $email): void
    {
        $subjectDir = $this->emailDir . DIRECTORY_SEPARATOR . 'subject' . DIRECTORY_SEPARATOR;
        $textDir = $this->emailDir . DIRECTORY_SEPARATOR . 'text' . DIRECTORY_SEPARATOR;
        $htmlDir = $this->emailDir . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR;

        $mailer = $this->mailer;
        $mailer->clearAllRecipients();

        $from = $email->getFrom();
        if ($from instanceof Address) {
            $mailer->setFrom($from->getAddress(), $from->getName() ?? '');
        }

        foreach ($email->getTo() as $to) {
            $mailer->addAddress($to->getAddress(), $to->getName() ?? '');
        }

        foreach ($email->getReplayTo() as $replyTo) {
            $mailer->addReplyTo($replyTo->getAddress(), $replyTo->getName() ?? '');
        }

        foreach ($email->getCc() as $cc) {
            $mailer->addCC($cc->getAddress(), $cc->getName() ?? '');
        }

        foreach ($email->getBcc() as $bcc) {
            $mailer->addBCC($bcc->getAddress(), $bcc->getName() ?? '');
        }

        $format = $email->getEmailFormat();
        $subject = $email->getSubject();
        $text = $email->getText();
        $html = $email->getHtml();

        $templateId = $email->getTemplateId();
        if ($templateId !== null) {
            $subject = $this->renderTemplate(
                $subjectDir . $templateId . '.txt',
                $email->getTemplateVars(),
            );
            $text = $this->renderTemplate(
                $textDir . $templateId . '.txt',
                $email->getTemplateVars(),
            );
            $html = $format->isHtml() ? $this->renderTemplate(
                $htmlDir . $templateId . '.html',
                $email->getTemplateVars(),
            ) : null;
        }

        if ($format->isHtml()) {
            $mailer->isHTML();
            $mailer->Subject = $subject ?? ''; // phpcs:ignore
            $mailer->Body = $html ?? ''; // phpcs:ignore
            $mailer->AltBody = $text ?? ''; // phpcs:ignore
            $mailer->send();

            return;
        }

        $mailer->isHTML(false);
        $mailer->Subject = $subject ?? ''; // phpcs:ignore
        $mailer->Body = $text ?? ''; // phpcs:ignore
        $mailer->AltBody = ''; // phpcs:ignore
        $mailer->send();
    }

    /** @param array<string, mixed> $vars */
    private function renderTemplate(string $filePath, array $vars = []): string
    {
        if (! is_readable($filePath)) {
            throw new TemplateNotFoundException($filePath);
        }

        return ($this->templateRenderer)($filePath, $vars); // phpcs:ignore
    }
}
