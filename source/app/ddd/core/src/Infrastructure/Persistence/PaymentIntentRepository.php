<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\PaymentIntent\PaymentGateway;
use AppCore\Domain\PaymentIntent\PaymentIntent;
use AppCore\Domain\PaymentIntent\PaymentIntentNotFoundException;
use AppCore\Domain\PaymentIntent\PaymentIntentRepositoryInterface;
use AppCore\Domain\PaymentIntent\PaymentIntentStatus;
use AppCore\Infrastructure\Query\PaymentIntentCommandInterface;
use AppCore\Infrastructure\Query\PaymentIntentQueryInterface;
use DateTimeImmutable;

use function assert;

/** @psalm-import-type PaymentIntentItem from PaymentIntentQueryInterface */
class PaymentIntentRepository implements PaymentIntentRepositoryInterface
{
    public function __construct(
        private readonly PaymentIntentCommandInterface $paymentIntentCommand,
        private readonly PaymentIntentQueryInterface $paymentIntentQuery,
    ) {
    }

    public function findByOrderId(int $orderId): PaymentIntent
    {
        $item = $this->paymentIntentQuery->itemByOrderId($orderId);
        if ($item === null) {
            throw new PaymentIntentNotFoundException();
        }

        return $this->toModel($item);
    }

    public function insert(PaymentIntent $paymentIntent): void
    {
        $result = $this->paymentIntentCommand->add(
            $paymentIntent->orderId,
            $paymentIntent->billingId,
            $paymentIntent->paymentMethodId,
            $paymentIntent->idempotencyToken,
            $paymentIntent->gateway->value,
            $paymentIntent->chargeAmount,
            $paymentIntent->captureAmount,
            $paymentIntent->authorization,
            $paymentIntent->authorizedDate,
            $paymentIntent->status->value,
        );

        $paymentIntent->setNewId($result['id']);
    }

    public function update(PaymentIntent $paymentIntent): void
    {
        if (empty($paymentIntent->id)) {
            return;
        }

        $this->paymentIntentCommand->update(
            $paymentIntent->id,
            $paymentIntent->captureAmount,
            $paymentIntent->authorization,
            $paymentIntent->authorizedDate,
            $paymentIntent->status->value,
        );
    }

    /**
     * @psalm-param PaymentIntentItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    public function toModel(array $item): PaymentIntent
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $orderId = (int) $item['order_id'];
        assert($orderId > 0);

        $billingId = (int) $item['billing_id'];
        assert($billingId > 0);

        $paymentMethodId = (int) $item['payment_method_id'];
        assert($paymentMethodId > 0);

        $chargeAmount = (int) $item['charge_amount'];
        assert($chargeAmount > 0);

        $captureAmount = (int) $item['capture_amount'];
        assert($captureAmount > 0);

        return PaymentIntent::reconstruct(
            $id,
            $orderId,
            $billingId,
            $paymentMethodId,
            $item['idempotency_token'],
            PaymentGateway::from($item['gateway']),
            $chargeAmount,
            $captureAmount,
            $item['authorization'],
            empty($item['authorized_date']) ? null : new DateTimeImmutable($item['authorized_date']),
            PaymentIntentStatus::from($item['status']),
        );
    }
}
