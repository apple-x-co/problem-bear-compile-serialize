<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\StoreFeePaymentIntent\StoreFeePaymentGateway;
use AppCore\Domain\StoreFeePaymentIntent\StoreFeePaymentIntent;
use AppCore\Domain\StoreFeePaymentIntent\StoreFeePaymentIntentNotFoundException;
use AppCore\Domain\StoreFeePaymentIntent\StoreFeePaymentIntentRepositoryInterface;
use AppCore\Domain\StoreFeePaymentIntent\StoreFeePaymentIntentStatus;
use AppCore\Infrastructure\Query\StoreFeePaymentIntentCommandInterface;
use AppCore\Infrastructure\Query\StoreFeePaymentIntentQueryInterface;
use DateTimeImmutable;

use function array_map;
use function assert;

/**
 * @psalm-import-type StoreFeePaymentIntentItem from StoreFeePaymentIntentQueryInterface
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class StoreFeePaymentIntentRepository implements StoreFeePaymentIntentRepositoryInterface
{
    public function __construct(
        private readonly StoreFeePaymentIntentCommandInterface $storeFeePaymentIntentCommand,
        private readonly StoreFeePaymentIntentQueryInterface $storeFeePaymentIntentQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): StoreFeePaymentIntent
    {
        $item = $this->storeFeePaymentIntentQuery->item($id);
        if ($item === null) {
            throw new StoreFeePaymentIntentNotFoundException();
        }

        return $this->toModel($item);
    }

    /**
     * @param int<1, max> $storeId
     *
     * @return list<StoreFeePaymentIntent>
     */
    public function findByStoreId(int $storeId): array
    {
        $items = $this->storeFeePaymentIntentQuery->listByStoreId($storeId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    /**
     * @param int<1, max> $storeUsageId
     *
     * @return list<StoreFeePaymentIntent>
     */
    public function findByStoreUsageId(int $storeUsageId): array
    {
        $items = $this->storeFeePaymentIntentQuery->listByStoreUsageId($storeUsageId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    /** @psalm-param StoreFeePaymentIntentItem $storeFeePaymentIntentItem */
    private function toModel(array $storeFeePaymentIntentItem): StoreFeePaymentIntent
    {
        $id = (int) $storeFeePaymentIntentItem['id'];
        assert($id > 0);

        $storeUsageBillingId = (int) $storeFeePaymentIntentItem['store_usage_billing_id'];
        assert($storeUsageBillingId > 0);

        $paymentMethodId = (int) $storeFeePaymentIntentItem['payment_method_id'];
        assert($paymentMethodId > 0);

        $chargeAmount = (int) $storeFeePaymentIntentItem['charge_amount'];
        assert($chargeAmount > 0);

        $captureAmount = (int) $storeFeePaymentIntentItem['capture_amount'];
        assert($captureAmount > -1);

        $refundedAmount = (int) $storeFeePaymentIntentItem['refunded_amount'];
        assert($refundedAmount > -1);

        return StoreFeePaymentIntent::reconstruct(
            $id,
            $storeUsageBillingId,
            $paymentMethodId,
            $storeFeePaymentIntentItem['idempotency_token'],
            StoreFeePaymentGateway::from($storeFeePaymentIntentItem['gateway']),
            $chargeAmount,
            $captureAmount,
            $refundedAmount,
            $storeFeePaymentIntentItem['authorization'],
            empty($storeFeePaymentIntentItem['authorized_date']) ?
                null :
                new DateTimeImmutable($storeFeePaymentIntentItem['authorized_date']),
            empty($storeFeePaymentIntentItem['cancel_date']) ?
                null :
                new DateTimeImmutable($storeFeePaymentIntentItem['cancel_date']),
            StoreFeePaymentIntentStatus::from($storeFeePaymentIntentItem['status']),
        );
    }

    public function insert(StoreFeePaymentIntent $storeFeePaymentIntent): void
    {
        $result = $this->storeFeePaymentIntentCommand->add(
            $storeFeePaymentIntent->storeUsageBillingId,
            $storeFeePaymentIntent->paymentMethodId,
            $storeFeePaymentIntent->idempotencyToken,
            $storeFeePaymentIntent->gateway->value,
            $storeFeePaymentIntent->chargeAmount,
            $storeFeePaymentIntent->captureAmount,
            $storeFeePaymentIntent->refundedAmount,
            $storeFeePaymentIntent->authorization,
            $storeFeePaymentIntent->authorizedDate,
            $storeFeePaymentIntent->cancelDate,
            $storeFeePaymentIntent->status->value,
        );

        $storeFeePaymentIntent->setNewId($result['id']);
    }

    public function update(StoreFeePaymentIntent $storeFeePaymentIntent): void
    {
        if (empty($storeFeePaymentIntent->id)) {
            return;
        }

        $this->storeFeePaymentIntentCommand->update(
            $storeFeePaymentIntent->id,
            $storeFeePaymentIntent->idempotencyToken,
            $storeFeePaymentIntent->captureAmount,
            $storeFeePaymentIntent->refundedAmount,
            $storeFeePaymentIntent->authorization,
            $storeFeePaymentIntent->authorizedDate,
            $storeFeePaymentIntent->cancelDate,
            $storeFeePaymentIntent->status->value,
        );
    }
}
