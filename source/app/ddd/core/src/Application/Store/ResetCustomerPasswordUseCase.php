<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\Customer\CustomerNotFoundException;
use AppCore\Domain\Customer\CustomerRepositoryInterface;
use AppCore\Domain\Customer\CustomerStatus;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\UrlSignature\ExpiredSignatureException;
use AppCore\Domain\UrlSignature\UrlSignatureEncrypterInterface;
use AppCore\Qualifier\NotifierAddress;
use AppCore\Qualifier\SmptTransport;
use DateTimeImmutable;
use Throwable;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
final class ResetCustomerPasswordUseCase
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
        #[NotifierAddress]
        private readonly AddressInterface $notifierAddress,
        private readonly PasswordHasherInterface $passwordHasher,
        #[SmptTransport]
        private readonly TransportInterface $transport,
        private readonly UrlSignatureEncrypterInterface $urlSignatureEncrypter,
    ) {
    }

    public function execute(ResetCustomerPasswordInputData $inputData): void
    {
        $now = new DateTimeImmutable();

        $forgotCustomerPasswordUrlSignature = $this->urlSignatureEncrypter->decrypt(
            $inputData->signature,
            ForgotCustomerPasswordUrlSignature::class,
        );

        if ($forgotCustomerPasswordUrlSignature->expiresAt < $now) {
            throw new ExpiredSignatureException();
        }

        $customer = $this->customerRepository->findByEmail($forgotCustomerPasswordUrlSignature->email);
        if (
            $customer === null ||
            $customer->status === CustomerStatus::DELETED ||
            $customer->phoneNumber !== $inputData->phoneNumber
        ) {
            throw new CustomerNotFoundException();
        }

        $customer = $customer->resetPassword($this->passwordHasher->hash($inputData->password));
        $this->customerRepository->update($customer);

        try {
            $this->transport->send(
                (new Email())
                    ->setFrom($this->notifierAddress)
                    ->setTo([new Address($forgotCustomerPasswordUrlSignature->email)])
                    ->setTemplateId('customer_reset_password'),
            );
        } catch (Throwable) {
            // Do nothing
        }
    }
}
