<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\StoreUsage\StoreUsage;
use AppCore\Domain\StoreUsage\StoreUsageBilling;
use AppCore\Domain\StoreUsage\StoreUsageBillingNotFoundException;
use AppCore\Domain\StoreUsage\StoreUsageBillingStatus;
use AppCore\Domain\StoreUsage\StoreUsageNotFoundException;
use AppCore\Domain\StoreUsage\StoreUsageRepositoryInterface;
use AppCore\Domain\StoreUsage\StoreUsageStatus;
use AppCore\Infrastructure\Query\StoreUsageBillingCommandInterface;
use AppCore\Infrastructure\Query\StoreUsageBillingQueryInterface;
use AppCore\Infrastructure\Query\StoreUsageCommandInterface;
use AppCore\Infrastructure\Query\StoreUsageQueryInterface;
use DateTimeImmutable;

use function array_map;
use function array_reduce;
use function assert;

/**
 * @psalm-import-type StoreUsageBillingItem from StoreUsageBillingQueryInterface
 * @psalm-import-type StoreUsageItem from StoreUsageQueryInterface
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class StoreUsageRepository implements StoreUsageRepositoryInterface
{
    public function __construct(
        private readonly StoreUsageBillingCommandInterface $storeUsageBillingCommand,
        private readonly StoreUsageBillingQueryInterface $storeUsageBillingQuery,
        private readonly StoreUsageCommandInterface $storeUsageCommand,
        private readonly StoreUsageQueryInterface $storeUsageQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): StoreUsage
    {
        $storeUsageItem = $this->storeUsageQuery->item($id);
        if ($storeUsageItem === null) {
            throw new StoreUsageNotFoundException();
        }

        $storeUsageBillingItem = $this->storeUsageBillingQuery->itemByStoreUsageId($id);
        if ($storeUsageBillingItem === null) {
            throw new StoreUsageBillingNotFoundException();
        }

        return $this->toModel($storeUsageItem, $storeUsageBillingItem);
    }

    /**
     * @param int<1, max> $storeId
     *
     * @return list<StoreUsage>
     */
    public function findByStoreId(int $storeId): array
    {
        $storeUsageItems = $this->storeUsageQuery->listByStoreId($storeId);

        $storeUsageBillingItems = $this->storeUsageBillingQuery->listByStoreId($storeId);
        $storeUsageBillingItemMap = array_reduce(
            $storeUsageBillingItems,
            static function (array $carry, array $item) {
                $carry[(int) $item['store_usage_id']] = $item;

                return $carry;
            },
            [],
        );

        return array_map(
            function (array $storeUsageItem) use ($storeUsageBillingItemMap) {
                $storeUsageBillingItem = $storeUsageBillingItemMap[(int) $storeUsageItem['id']] ?? null;
                if ($storeUsageBillingItem === null) {
                    throw new StoreUsageBillingNotFoundException();
                }

                return $this->toModel($storeUsageItem, $storeUsageBillingItem);
            },
            $storeUsageItems,
        );
    }

    /**
     * @psalm-param StoreUsageItem $storeUsageItem
     * @psalm-param StoreUsageBillingItem $storeUsageBillingItem
     */
    private function toModel(array $storeUsageItem, array $storeUsageBillingItem): StoreUsage
    {
        $id = (int) $storeUsageItem['id'];
        assert($id > 0);

        $storeId = (int) $storeUsageItem['store_id'];
        assert($storeId > 0);

        $totalPrice = (int) $storeUsageItem['total_price'];
        assert($totalPrice > 0);

        return StoreUsage::reconstruct(
            $id,
            $storeId,
            $storeUsageItem['description'],
            new DateTimeImmutable($storeUsageItem['target_date']),
            $totalPrice,
            $this->toStoreUsageBilling($storeUsageBillingItem),
            StoreUsageStatus::from($storeUsageItem['status']),
        );
    }

    /** @psalm-param StoreUsageBillingItem $storeUsageBillingItem */
    private function toStoreUsageBilling(array $storeUsageBillingItem): StoreUsageBilling
    {
        $id = (int) $storeUsageBillingItem['id'];
        assert($id > 0);

        $chargeAmount = (int) $storeUsageBillingItem['charge_amount'];
        assert($chargeAmount > 0);

        return StoreUsageBilling::reconstruct(
            $id,
            $chargeAmount,
            new DateTimeImmutable($storeUsageBillingItem['billing_date']),
            new DateTimeImmutable($storeUsageBillingItem['scheduled_payment_date']),
            new DateTimeImmutable($storeUsageBillingItem['due_date']),
            empty($storeUsageBillingItem['paid_date']) ?
                null :
                new DateTimeImmutable($storeUsageBillingItem['paid_date']),
            $storeUsageBillingItem['family_name'],
            $storeUsageBillingItem['given_name'],
            $storeUsageBillingItem['phonetic_family_name'],
            $storeUsageBillingItem['phonetic_given_name'],
            $storeUsageBillingItem['postal_code'],
            $storeUsageBillingItem['state'],
            $storeUsageBillingItem['city'],
            $storeUsageBillingItem['address_line_1'],
            $storeUsageBillingItem['address_line_2'],
            $storeUsageBillingItem['phone_number'],
            $storeUsageBillingItem['email'],
            StoreUsageBillingStatus::from($storeUsageBillingItem['status']),
        );
    }

    public function insert(StoreUsage $storeUsage): void
    {
        $result = $this->storeUsageCommand->add(
            $storeUsage->storeId,
            $storeUsage->description,
            $storeUsage->targetDate,
            $storeUsage->totalPrice,
            $storeUsage->status->value,
        );

        $storeUsageId = $result['id'];
        $storeUsage->setNewId($storeUsageId);

        $storeUsageBilling = $storeUsage->billing;
        $this->storeUsageBillingCommand->add(
            $storeUsageId,
            $storeUsageBilling->chargeAmount,
            $storeUsageBilling->billingDate,
            $storeUsageBilling->scheduledPaymentDate,
            $storeUsageBilling->dueDate,
            $storeUsageBilling->paidDate,
            $storeUsageBilling->familyName,
            $storeUsageBilling->givenName,
            $storeUsageBilling->phoneticFamilyName,
            $storeUsageBilling->phoneticGivenName,
            $storeUsageBilling->postalCode,
            $storeUsageBilling->state,
            $storeUsageBilling->city,
            $storeUsageBilling->addressLine1,
            $storeUsageBilling->addressLine2,
            $storeUsageBilling->phoneNumber,
            $storeUsageBilling->email,
            $storeUsageBilling->status->value,
        );
    }

    public function update(StoreUsage $storeUsage): void
    {
        if (empty($storeUsage->id)) {
            return;
        }

        $storeUsageBilling = $storeUsage->billing;
        if (empty($storeUsageBilling->id)) {
            return;
        }

        $this->storeUsageCommand->update($storeUsage->id, $storeUsage->status->value);

        $this->storeUsageBillingCommand->update(
            $storeUsageBilling->id,
            $storeUsageBilling->paidDate,
            $storeUsageBilling->status->value,
        );
    }
}
