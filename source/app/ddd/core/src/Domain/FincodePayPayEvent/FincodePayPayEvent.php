<?php

declare(strict_types=1);

namespace AppCore\Domain\FincodePayPayEvent;

use AppCore\Domain\Fincode\FincodeJobCd;
use AppCore\Domain\Fincode\FincodePayType;
use AppCore\Domain\Fincode\FincodeStatus;

final class FincodePayPayEvent
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max> $amount
     * @param int<1, max> $tax
     * @param int<0, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly string $shopId,
        public readonly string $accessId,
        public readonly string $orderId,
        public readonly int $amount,
        public readonly int $tax,
        public readonly string $customerId,
        public readonly string $processDate,
        public readonly string $codeExpiryDate,
        public readonly string $authMaxDate,
        public readonly string $codeId,
        public readonly string $paymentId,
        public readonly string $paymentDate,
        public readonly string $errorCode,
        public readonly FincodePayType $payType,
        public readonly string $event,
        public readonly FincodeJobCd $jobCd,
        public readonly FincodeStatus $status,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $id
     * @param int<1, max> $amount
     * @param int<1, max> $tax
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
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
        string $paymentDate,
        string $errorCode,
        FincodePayType $payType,
        string $event,
        FincodeJobCd $jobCd,
        FincodeStatus $status,
    ): self {
        return new self(
            $shopId,
            $accessId,
            $orderId,
            $amount,
            $tax,
            $customerId,
            $processDate,
            $codeExpiryDate,
            $authMaxDate,
            $codeId,
            $paymentId,
            $paymentDate,
            $errorCode,
            $payType,
            $event,
            $jobCd,
            $status,
            $id,
        );
    }

    /** @return int<1, max>|null */
    public function getNewId(): int|null
    {
        return $this->newId;
    }

    /** @param int<1, max> $newId */
    public function setNewId(int $newId): void
    {
        $this->newId = $newId;
    }
}
