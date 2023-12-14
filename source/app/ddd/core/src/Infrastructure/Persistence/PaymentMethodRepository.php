<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\PaymentMethod\PaymentMethod;
use AppCore\Domain\PaymentMethod\PaymentMethodKey;
use AppCore\Domain\PaymentMethod\PaymentMethodNotFoundException;
use AppCore\Domain\PaymentMethod\PaymentMethodRole;
use AppCore\Infrastructure\Query\PaymentMethodCommandInterface;
use AppCore\Infrastructure\Query\PaymentMethodQueryInterface;

use function assert;

/** @psalm-import-type PaymentMethodItem from PaymentMethodQueryInterface */
class PaymentMethodRepository
{
    public function __construct(
        private readonly PaymentMethodCommandInterface $paymentMethodCommand,
        protected readonly PaymentMethodQueryInterface $paymentMethodQuery,
    ) {
    }

    /** @param int<1, max> $id */
    protected function findById(int $id): PaymentMethod
    {
        $item = $this->paymentMethodQuery->item($id);
        if ($item === null) {
            throw new PaymentMethodNotFoundException();
        }

        return $this->toModel($item);
    }

    protected function insert(PaymentMethod $paymentMethod): void
    {
        $result = $this->paymentMethodCommand->add(
            $paymentMethod->name,
            $paymentMethod->key->value,
            $paymentMethod->role->value,
            $paymentMethod->fee,
            (int) $paymentMethod->available,
            $paymentMethod->position,
        );

        $paymentMethod->setNewId($result['id']);
    }

    protected function update(PaymentMethod $paymentMethod): void
    {
        if (empty($paymentMethod->id)) {
            return;
        }

        $this->paymentMethodCommand->update(
            $paymentMethod->id,
            $paymentMethod->name,
            $paymentMethod->fee,
            (int) $paymentMethod->available,
            $paymentMethod->position,
        );
    }

    /**
     * @psalm-param PaymentMethodItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    protected function toModel(array $item): PaymentMethod
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $fee = (int) $item['fee'];
        assert($fee > -1);

        $position = (int) $item['position'];
        assert($position > 0);

        return PaymentMethod::reconstruct(
            $id,
            $item['name'],
            PaymentMethodKey::from($item['key']),
            PaymentMethodRole::from($item['role']),
            $fee,
            (bool) $item['available'],
            $position,
        );
    }
}
