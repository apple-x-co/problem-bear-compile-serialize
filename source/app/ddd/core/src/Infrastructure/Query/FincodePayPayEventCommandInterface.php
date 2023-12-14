<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface FincodePayPayEventCommandInterface
{
    /**
     * @param int<1, max> $amount
     * @param int<1, max> $tax
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('fincode_paypay_event_add', type: 'row')]
    public function add(
        string $shopId,
        string $accessId,
        string $orderId,
        int $amount,
        int $tax,
        string $customerId,
        string $processDate,
        string $codeExpiryDate,
        string $authMaxDate,
        string $codeId,
        string $paymentId,
        string|null $paymentDate,
        string|null $errorCode,
        string $payType,
        string $event,
        string $jobCd,
        string $status,
    ): array;

    /**
     * @param int<1, max> $id
     * @param int<1, max> $amount
     * @param int<1, max> $tax
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    #[DbQuery('fincode_paypay_event_update', type: 'row')]
    public function update(
        int $id,
        int $amount,
        int $tax,
        string $processDate,
        string $codeExpiryDate,
        string $authMaxDate,
        string $codeId,
        string $paymentId,
        string|null $paymentDate,
        string|null $errorCode,
        string $payType,
        string $event,
        string $jobCd,
        string $status,
    ): void;
}
