<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use AppCore\Domain\Mail\EmailConfigInterface;
use PHPMailer\PHPMailer\PHPMailer;
use Ray\Di\ProviderInterface;

/**
 * メーラーを提供
 *
 * @implements ProviderInterface<PHPMailer>
 */
class PhpMailerProvider implements ProviderInterface
{
    public function __construct(private readonly EmailConfigInterface $emailConfig)
    {
    }

    public function get(): PHPMailer
    {
        $phpMailer = new PHPMailer(true);
        $phpMailer->isSMTP();
        $phpMailer->CharSet = 'UTF-8'; // phpcs:ignore
        $phpMailer->Host = $this->emailConfig->getHostname(); // phpcs:ignore
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = $this->emailConfig->getUsername(); // phpcs:ignore
        $phpMailer->Password = $this->emailConfig->getPassword(); // phpcs:ignore

        $port = $this->emailConfig->getPort();
        $phpMailer->Port = $port; // phpcs:ignore
        $phpMailer->SMTPSecure = match ($port) {
            465 => PHPMailer::ENCRYPTION_SMTPS,
            587 => PHPMailer::ENCRYPTION_STARTTLS,
            default => '',
        };
        $phpMailer->SMTPOptions = $this->emailConfig->getOptions();

        return $phpMailer;
    }
}
