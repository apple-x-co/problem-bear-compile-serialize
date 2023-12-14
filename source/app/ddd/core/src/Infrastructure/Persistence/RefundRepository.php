<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Refund\Refund;
use AppCore\Domain\Refund\RefundNotFoundException;
use AppCore\Domain\Refund\RefundRepositoryInterface;
use AppCore\Domain\Refund\RefundStatus;
use AppCore\Infrastructure\Query\RefundCommandInterface;
use AppCore\Infrastructure\Query\RefundQueryInterface;

use function assert;

/** @psalm-import-type RefundItem from RefundQueryInterface */
class RefundRepository implements RefundRepositoryInterface
{
    public function __construct(
        private readonly RefundCommandInterface $refundCommand,
        private readonly RefundQueryInterface $refundQuery,
    ) {
    }

    /** @param int<1, max> $orderId */
    public function findByOrderId(int $orderId): Refund
    {
        $item = $this->refundQuery->itemByOrderId($orderId);
        if ($item === null) {
            throw new RefundNotFoundException();
        }

        return $this->toModel($item);
    }

    public function insert(Refund $refund): void
    {
        $result = $this->refundCommand->add(
            $refund->orderId,
            $refund->refundedAmount,
            $refund->status->value,
        );

        $refund->setNewId($result['id']);
    }

    public function update(Refund $refund): void
    {
        if (empty($refund->id)) {
            return;
        }

        $this->refundCommand->update(
            $refund->id,
            $refund->status->value,
        );
    }

    /**
     * @psalm-param RefundItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): Refund
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $orderId = (int) $item['order_id'];
        assert($orderId > 0);

        $refundedAmount = (int) $item['refunded_amount'];
        assert($refundedAmount > -1);

        return Refund::reconstruct(
            $id,
            $orderId,
            $refundedAmount,
            RefundStatus::from($item['status']),
        );
    }
}
