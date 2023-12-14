<?php

declare(strict_types=1);

namespace AppCore\Application\Store;

use AppCore\Domain\Customer\Customer;
use AppCore\Domain\Customer\CustomerRepositoryInterface;
use AppCore\Domain\Customer\CustomerStatus;
use AppCore\Domain\GenderType;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\UrlSignature\UrlSignatureEncrypterInterface;
use AppCore\Qualifier\NotifierAddress;
use AppCore\Qualifier\SmptTransport;
use AppCore\Qualifier\WebsiteUrl;
use DateTimeImmutable;

use function assert;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class JoinCustomerUseCase
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
        #[NotifierAddress]
        private readonly AddressInterface $notifierAddress,
        private readonly PasswordHasherInterface $passwordHasher,
        #[SmptTransport]
        private readonly TransportInterface $transport,
        private readonly UrlSignatureEncrypterInterface $urlSignatureEncrypter,
        #[WebsiteUrl]
        private readonly string $websiteUrl,
    ) {
    }

    public function execute(JoinCustomerInputData $inputData): void
    {
        $customer = $this->customerRepository->findByEmail($inputData->email);
        if ($customer !== null && $customer->status !== CustomerStatus::TEMPORARY) {
            return;
        }

        $customer = $this->join($inputData, $customer);
        $customerId = $customer->getNewId() ?? $customer->id;
        assert($customerId > 0);

        $expiresAt = (new DateTimeImmutable())->modify('+24 hours');
        $emailWebSignature = new JoinCustomerUrlSignature(
            $customerId,
            $expiresAt,
            $inputData->phoneNumber,
            $inputData->email,
        );
        $encrypted = $this->urlSignatureEncrypter->encrypt($emailWebSignature);

        $this->transport->send(
            (new Email())
                ->setFrom($this->notifierAddress)
                ->setTo([new Address($inputData->email)])
                ->setTemplateId('customer_join')
                ->setTemplateVars([
                    'name' => $inputData->familyName,
                    'expiresAt' => $expiresAt,
                    'websiteUrl' => $this->websiteUrl,
                    'signature' => $encrypted,
                ]),
        );
    }

    private function join(JoinCustomerInputData $inputData, Customer|null $customer): Customer
    {
        if ($customer === null) {
            $customer = new Customer(
                $inputData->familyName,
                $inputData->givenName,
                $inputData->phoneticFamilyName,
                $inputData->phoneticGivenName,
                GenderType::from($inputData->genderType),
                $inputData->phoneNumber,
                $inputData->email,
                $this->passwordHasher->hash($inputData->password),
                new DateTimeImmutable(),
            );
            $this->customerRepository->insert($customer);

            return $customer;
        }

        $customer = $customer->rejoin(
            $inputData->familyName,
            $inputData->givenName,
            $inputData->phoneticFamilyName,
            $inputData->phoneticGivenName,
            GenderType::from($inputData->genderType),
            $inputData->phoneNumber,
            $inputData->email,
            $this->passwordHasher->hash($inputData->password),
            new DateTimeImmutable(),
        );
        $this->customerRepository->update($customer);

        return $customer;
    }
}
