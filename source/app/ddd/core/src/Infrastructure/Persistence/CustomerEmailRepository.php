<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\CustomerEmail\CustomerEmail;
use AppCore\Domain\CustomerEmail\CustomerEmailNotFoundException;
use AppCore\Domain\CustomerEmail\CustomerEmailRepositoryInterface;
use AppCore\Infrastructure\Query\CustomerEmailCommandInterface;
use AppCore\Infrastructure\Query\CustomerEmailQueryInterface;
use DateTimeImmutable;

use function assert;

/** @psalm-import-type CustomerEmailItem from CustomerEmailQueryInterface */
class CustomerEmailRepository implements CustomerEmailRepositoryInterface
{
    public function __construct(
        private readonly CustomerEmailCommandInterface $customerEmailCommand,
        private readonly CustomerEmailQueryInterface $customerEmailQuery,
    ) {
    }

    public function findByToken(string $token): CustomerEmail
    {
        $item = $this->customerEmailQuery->itemByToken($token);
        if ($item === null) {
            throw new CustomerEmailNotFoundException($token);
        }

        return $this->toModel($item);
    }

    public function insert(CustomerEmail $customerEmail): void
    {
        $result = $this->customerEmailCommand->add(
            $customerEmail->customerId,
            $customerEmail->email,
            $customerEmail->token,
            $customerEmail->expireDate,
            $customerEmail->verifiedDate,
        );

        $customerEmail->setNewId($result['id']);
    }

    public function update(CustomerEmail $customerEmail): void
    {
        if (empty($customerEmail->id)) {
            return;
        }

        if ($customerEmail->verifiedDate === null) {
            return;
        }

        $this->customerEmailCommand->update(
            $customerEmail->id,
            $customerEmail->verifiedDate,
        );
    }

    /**
     * @psalm-param CustomerEmailItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): CustomerEmail
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $customerId = (int) $item['customer_id'];
        assert($customerId > 0);

        return CustomerEmail::reconstruct(
            $id,
            $customerId,
            $item['email'],
            $item['token'],
            new DateTimeImmutable($item['expire_date']),
            empty($item['verified_date']) ? null : new DateTimeImmutable($item['verified_date']),
        );
    }
}
