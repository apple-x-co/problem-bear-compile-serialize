<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Fincode\FincodeJobCd;
use AppCore\Domain\Fincode\FincodePayType;
use AppCore\Domain\Fincode\FincodeStatus;
use AppCore\Domain\FincodePayPayEvent\FincodePayPayEvent;
use AppCore\Domain\FincodePayPayEvent\FincodePayPayEventNotFoundException;
use AppCore\Domain\FincodePayPayEvent\FincodePayPayEventRepositoryInterface;
use AppCore\Infrastructure\Query\FincodePayPayEventCommandInterface;
use AppCore\Infrastructure\Query\FincodePayPayEventQueryInterface;

use function assert;

/**
 * @psalm-import-type FincodePayPayEventItem from FincodePayPayEventQueryInterface
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class FincodePayPayEventRepository implements FincodePayPayEventRepositoryInterface
{
    public function __construct(
        private readonly FincodePayPayEventCommandInterface $fincodePayPayEventCommand,
        private readonly FincodePayPayEventQueryInterface $fincodePayPayEventQuery,
    ) {
    }

    public function findByAccessId(string $accessId): FincodePayPayEvent
    {
        $item = $this->fincodePayPayEventQuery->itemByAccessId($accessId);
        if ($item === null) {
            throw new FincodePayPayEventNotFoundException();
        }

        return $this->toModel($item);
    }

    /** @psalm-param FincodePayPayEventItem $item */
    private function toModel(array $item): FincodePayPayEvent
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $amount = (int) $item['amount'];
        assert($amount > 0);

        $tax = (int) $item['tax'];
        assert($tax > 0);

        return FincodePayPayEvent::reconstruct(
            $id,
            $item['shop_id'],
            $item['access_id'],
            $item['order_id'],
            $amount,
            $tax,
            $item['customer_id'],
            $item['process_date'],
            $item['code_expiry_date'],
            $item['auth_max_date'],
            $item['code_id'],
            $item['payment_id'],
            $item['payment_date'],
            $item['error_code'],
            FincodePayType::from($item['pay_type']),
            $item['event'],
            FincodeJobCd::from($item['job_cd']),
            FincodeStatus::from($item['status']),
        );
    }

    public function insert(FincodePayPayEvent $fincodePayPayEvent): void
    {
        $result = $this->fincodePayPayEventCommand->add(
            $fincodePayPayEvent->shopId,
            $fincodePayPayEvent->accessId,
            $fincodePayPayEvent->orderId,
            $fincodePayPayEvent->amount,
            $fincodePayPayEvent->tax,
            $fincodePayPayEvent->customerId,
            $fincodePayPayEvent->processDate,
            $fincodePayPayEvent->codeExpiryDate,
            $fincodePayPayEvent->authMaxDate,
            $fincodePayPayEvent->codeId,
            $fincodePayPayEvent->paymentId,
            $fincodePayPayEvent->paymentDate,
            $fincodePayPayEvent->errorCode,
            $fincodePayPayEvent->payType->value,
            $fincodePayPayEvent->event,
            $fincodePayPayEvent->jobCd->value,
            $fincodePayPayEvent->status->value,
        );

        $fincodePayPayEvent->setNewId($result['id']);
    }

    public function update(FincodePayPayEvent $fincodePayPayEvent): void
    {
        if (empty($fincodePayPayEvent->id)) {
            return;
        }

        $this->fincodePayPayEventCommand->update(
            $fincodePayPayEvent->id,
            $fincodePayPayEvent->amount,
            $fincodePayPayEvent->tax,
            $fincodePayPayEvent->processDate,
            $fincodePayPayEvent->codeExpiryDate,
            $fincodePayPayEvent->authMaxDate,
            $fincodePayPayEvent->codeId,
            $fincodePayPayEvent->paymentId,
            $fincodePayPayEvent->paymentDate,
            $fincodePayPayEvent->errorCode,
            $fincodePayPayEvent->payType->value,
            $fincodePayPayEvent->event,
            $fincodePayPayEvent->jobCd->value,
            $fincodePayPayEvent->status->value,
        );
    }
}
