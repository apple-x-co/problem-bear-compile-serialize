<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Customer\Customer;
use AppCore\Domain\Customer\CustomerNotFoundException;
use AppCore\Domain\Customer\CustomerRepositoryInterface;
use AppCore\Domain\Customer\CustomerStatus;
use AppCore\Domain\GenderType;
use AppCore\Infrastructure\Query\CustomerCommandInterface;
use AppCore\Infrastructure\Query\CustomerQueryInterface;
use DateTimeImmutable;

use function assert;

/** @psalm-import-type CustomerItem from CustomerQueryInterface */
class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(
        private readonly CustomerCommandInterface $customerCommand,
        private readonly CustomerQueryInterface $customerQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): Customer
    {
        $item = $this->customerQuery->item($id);
        if ($item === null) {
            throw new CustomerNotFoundException();
        }

        return $this->toModel($item);
    }

    public function findByEmail(string $email): Customer|null
    {
        $item = $this->customerQuery->itemByEmail($email);
        if ($item === null) {
            return null;
        }

        return $this->toModel($item);
    }

    public function insert(Customer $customer): void
    {
        $result = $this->customerCommand->add(
            $customer->familyName,
            $customer->givenName,
            $customer->phoneticFamilyName,
            $customer->phoneticGivenName,
            $customer->genderType->value,
            $customer->phoneNumber,
            $customer->email,
            $customer->password,
            $customer->joinedDate,
            $customer->status->value,
        );

        $customer->setNewId($result['id']);
    }

    public function update(Customer $customer): void
    {
        if (empty($customer->id)) {
            return;
        }

        $this->customerCommand->update(
            $customer->id,
            $customer->familyName,
            $customer->givenName,
            $customer->phoneticFamilyName,
            $customer->phoneticGivenName,
            $customer->genderType->value,
            $customer->phoneNumber,
            $customer->email,
            $customer->password,
            $customer->joinedDate,
            $customer->status->value,
        );
    }

    /**
     * @psalm-param CustomerItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): Customer
    {
        $id = (int) $item['id'];
        assert($id > 0);

        return Customer::reconstruct(
            $id,
            $item['family_name'],
            $item['given_name'],
            $item['phonetic_family_name'],
            $item['phonetic_given_name'],
            GenderType::from($item['gender_type']),
            $item['phone_number'],
            $item['email'],
            $item['password'],
            new DateTimeImmutable($item['joined_date']),
            CustomerStatus::from($item['status']),
        );
    }
}
