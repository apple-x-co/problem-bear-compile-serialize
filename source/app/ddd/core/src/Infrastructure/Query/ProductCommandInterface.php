<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface ProductCommandInterface
{
    /**
     * @param int<1, max>      $storeId
     * @param int<0, 1>        $taxable
     * @param int<1, max>|null $taxId
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $price
     * @param int<1, max>      $numberOfPieces
     * @param int<1, 100>|null $discountRate
     * @param int<min, 0>|null $discountPrice
     * @param int<0, 1>        $stockistNotificationEnabled
     *
     * @return array{id: int<1, max>}
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    #[DbQuery('product_add', type: 'row')]
    public function add(
        int $storeId,
        string $title,
        string $code,
        int $taxable,
        int|null $taxId,
        int $originalPrice,
        int $price,
        int $numberOfPieces,
        int|null $discountRate,
        int|null $discountPrice,
        int $stockistNotificationEnabled,
        string|null $stockistName,
        DateTimeImmutable|null $saleStartDate,
        DateTimeImmutable|null $saleEndDate,
        string $status,
    ): array;

    /**
     * @param int<1, max>      $id
     * @param int<0, 1>        $taxable
     * @param int<1, max>|null $taxId
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $price
     * @param int<1, max>      $numberOfPieces
     * @param int<1, 100>|null $discountRate
     * @param int<min, 0>|null $discountPrice
     * @param int<0, 1>        $stockistNotificationEnabled
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    #[DbQuery('product_update', type: 'row')]
    public function update(
        int $id,
        string $title,
        string $code,
        int $taxable,
        int|null $taxId,
        int $originalPrice,
        int $price,
        int $numberOfPieces,
        int|null $discountRate,
        int|null $discountPrice,
        int $stockistNotificationEnabled,
        string|null $stockistName,
        DateTimeImmutable|null $saleStartDate,
        DateTimeImmutable|null $saleEndDate,
        string $status,
    ): void;
}
