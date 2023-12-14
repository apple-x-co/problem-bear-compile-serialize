<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\UrlSignature\UrlSignatureEncrypterInterface;
use AppCore\Qualifier\NotifierAddress;
use AppCore\Qualifier\SmptTransport;
use AppCore\Qualifier\WebsiteUrl;
use DateTimeImmutable;

/** @SuppressWarnings(PHPMD.LongVariable) */
class ForgotCustomerPasswordUseCase
{
    public function __construct(
        #[NotifierAddress]
        private readonly AddressInterface $notifierAddress,
        #[SmptTransport]
        private readonly TransportInterface $transport,
        private readonly UrlSignatureEncrypterInterface $urlSignatureEncrypter,
        #[WebsiteUrl]
        private readonly string $websiteUrl,
    ) {
    }

    public function execute(ForgotCustomerPasswordInputData $inputData): void
    {
        $expiresAt = (new DateTimeImmutable())->modify('+10 minutes');

        $urlSignature = new ForgotCustomerPasswordUrlSignature(
            $expiresAt,
            $inputData->email,
        );
        $encrypted = $this->urlSignatureEncrypter->encrypt($urlSignature);

        $this->transport->send(
            (new Email())
                ->setFrom($this->notifierAddress)
                ->setTo([new Address($inputData->email)])
                ->setTemplateId('customer_forgot_password')
                ->setTemplateVars([
                    'expiresAt' => $expiresAt,
                    'websiteUrl' => $this->websiteUrl,
                    'signature' => $encrypted,
                ]),
        );
    }
}
