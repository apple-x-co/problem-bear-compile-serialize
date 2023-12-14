<?php

declare(strict_types=1);

namespace AppCore\Domain\Product;

use DateTimeImmutable;

final class Product
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $taxId
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $price
     * @param int<1, max>      $numberOfPieces
     * @param int<1, 100>|null $discountRate
     * @param int<min, 0>|null $discountPrice
     * @param int<0, max>      $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(
        public readonly int $storeId,
        public readonly string $title,
        public readonly string $code,
        public readonly bool $taxable,
        public readonly int|null $taxId,
        public readonly int $originalPrice,
        public readonly int $price,
        public readonly int $numberOfPieces,
        public readonly int|null $discountRate,
        public readonly int|null $discountPrice,
        public readonly bool $stockistNotificationEnabled,
        public readonly string|null $stockistName,
        public readonly DateTimeImmutable|null $saleStartDate,
        public readonly DateTimeImmutable|null $saleEndDate,
        public readonly ProductContent|null $content,
        public readonly ProductVariants $variants,
        public readonly ProductTaxonomies|null $productTaxonomies,
        public readonly ProductMakers|null $productMakers,
        public readonly ProductPurposes|null $productPurposes,
        public readonly ProductFeaturedImages|null $productFeaturedImages,
        public readonly ProductStatus $status,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $taxId
     * @param int<0, max>      $originalPrice
     * @param int<0, max>      $price
     * @param int<1, max>      $numberOfPieces
     * @param int<1, 100>|null $discountRate
     * @param int<min, 0>|null $discountPrice
     * @param int<1, max>      $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public static function reconstruct(
        int $id,
        int $storeId,
        string $title,
        string $code,
        bool $taxable,
        int|null $taxId,
        int $originalPrice,
        int $price,
        int $numberOfPieces,
        int|null $discountRate,
        int|null $discountPrice,
        bool $stockistNotificationEnabled,
        string|null $stockistName,
        DateTimeImmutable|null $saleStartDate,
        DateTimeImmutable|null $saleEndDate,
        ProductContent|null $content,
        ProductVariants $variants,
        ProductTaxonomies|null $productTaxonomies,
        ProductMakers|null $productMakers,
        ProductPurposes|null $productPurposes,
        ProductFeaturedImages|null $productFeaturedImages,
        ProductStatus $status,
    ): Product {
        return new self(
            $storeId,
            $title,
            $code,
            $taxable,
            $taxId,
            $originalPrice,
            $price,
            $numberOfPieces,
            $discountRate,
            $discountPrice,
            $stockistNotificationEnabled,
            $stockistName,
            $saleStartDate,
            $saleEndDate,
            $content,
            $variants,
            $productTaxonomies,
            $productMakers,
            $productPurposes,
            $productFeaturedImages,
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

    private function changeStatus(ProductStatus $status): self
    {
        return new self(
            $this->storeId,
            $this->title,
            $this->code,
            true,
            $this->taxId,
            $this->originalPrice,
            $this->price,
            $this->numberOfPieces,
            $this->discountRate,
            $this->discountPrice,
            $this->stockistNotificationEnabled,
            $this->stockistName,
            $this->saleStartDate === null ? null : clone $this->saleStartDate,
            $this->saleEndDate === null ? null : clone $this->saleEndDate,
            $this->content === null ? null : clone $this->content,
            clone $this->variants,
            $this->productTaxonomies === null ? null : clone $this->productTaxonomies,
            $this->productMakers === null ? null : clone $this->productMakers,
            $this->productPurposes === null ? null : clone $this->productPurposes,
            $this->productFeaturedImages === null ? null : clone $this->productFeaturedImages,
            $this->status->transitionTo($status),
            $this->id,
        );
    }

    public function draft(): self
    {
        return $this->changeStatus(ProductStatus::DELETED);
    }

    public function active(): self
    {
        return $this->changeStatus(ProductStatus::ACTIVE);
    }

    public function deleted(): self
    {
        return $this->changeStatus(ProductStatus::DELETED);
    }

    /** @param int<1, max> $taxId */
    public function enableTax(int $taxId): self
    {
        return new self(
            $this->storeId,
            $this->title,
            $this->code,
            true,
            $taxId,
            $this->originalPrice,
            $this->price,
            $this->numberOfPieces,
            $this->discountRate,
            $this->discountPrice,
            $this->stockistNotificationEnabled,
            $this->stockistName,
            $this->saleStartDate === null ? null : clone $this->saleStartDate,
            $this->saleEndDate === null ? null : clone $this->saleEndDate,
            $this->content === null ? null : clone $this->content,
            clone $this->variants,
            $this->productTaxonomies === null ? null : clone $this->productTaxonomies,
            $this->productMakers === null ? null : clone $this->productMakers,
            $this->productPurposes === null ? null : clone $this->productPurposes,
            $this->productFeaturedImages === null ? null : clone $this->productFeaturedImages,
            $this->status,
            $this->id,
        );
    }

    public function disableTax(): self
    {
        return new self(
            $this->storeId,
            $this->title,
            $this->code,
            false,
            null,
            $this->originalPrice,
            $this->price,
            $this->numberOfPieces,
            $this->discountRate,
            $this->discountPrice,
            $this->stockistNotificationEnabled,
            $this->stockistName,
            $this->saleStartDate === null ? null : clone $this->saleStartDate,
            $this->saleEndDate === null ? null : clone $this->saleEndDate,
            $this->content === null ? null : clone $this->content,
            clone $this->variants,
            $this->productTaxonomies === null ? null : clone $this->productTaxonomies,
            $this->productMakers === null ? null : clone $this->productMakers,
            $this->productPurposes === null ? null : clone $this->productPurposes,
            $this->productFeaturedImages === null ? null : clone $this->productFeaturedImages,
            $this->status,
            $this->id,
        );
    }
}
