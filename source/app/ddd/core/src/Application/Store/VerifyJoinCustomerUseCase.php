<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\Customer\CustomerRepositoryInterface;
use AppCore\Domain\Customer\CustomerStatus;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\UrlSignature\InvalidSignatureException;
use AppCore\Domain\UrlSignature\UrlSignatureEncrypterInterface;
use AppCore\Qualifier\NotifierAddress;
use AppCore\Qualifier\SmptTransport;
use AppCore\Qualifier\StoreLogger;
use AppCore\Qualifier\WebsiteUrl;
use Throwable;

use function assert;

/** @SuppressWarnings(PHPMD.LongVariable) */
final class VerifyJoinCustomerUseCase
{
    public function __construct(
        #[StoreLogger]
        private readonly LoggerInterface $customerLogger,
        private readonly CustomerRepositoryInterface $customerRepository,
        #[NotifierAddress]
        private readonly AddressInterface $notifierAddress,
        #[SmptTransport]
        private readonly TransportInterface $transport,
        private readonly UrlSignatureEncrypterInterface $urlSignatureEncrypter,
        #[WebsiteUrl]
        private readonly string $websiteUrl,
    ) {
    }

    public function execute(VerifyJoinCustomerInputData $inputData): void
    {
        $joinCustomerUrlSignature = $this->urlSignatureEncrypter->decrypt(
            $inputData->signature,
            JoinCustomerUrlSignature::class,
        );

        assert($joinCustomerUrlSignature->customerId > 0);

        $customer = $this->customerRepository->findById($joinCustomerUrlSignature->customerId);
        if (
            $customer->status !== CustomerStatus::TEMPORARY ||
            $customer->email !== $joinCustomerUrlSignature->email ||
            $customer->phoneNumber !== $joinCustomerUrlSignature->phoneNumber
        ) {
            throw new InvalidSignatureException();
        }

        $customer = $customer->verified();
        $this->customerRepository->update($customer);

        try {
            $this->transport->send(
                (new Email())
                    ->setFrom($this->notifierAddress)
                    ->setTo([new Address($joinCustomerUrlSignature->email)])
                    ->setTemplateId('customer_verified')
                    ->setTemplateVars([
                        'name' => $customer->familyName,
                        'websiteUrl' => $this->websiteUrl,
                    ]),
            );
        } catch (Throwable $throwable) {
            $this->customerLogger->log((string) $throwable);
        }
    }
}
