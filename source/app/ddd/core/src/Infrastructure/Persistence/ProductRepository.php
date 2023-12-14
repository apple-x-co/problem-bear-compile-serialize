<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Product\Product;
use AppCore\Domain\Product\ProductContent;
use AppCore\Domain\Product\ProductFeaturedImage;
use AppCore\Domain\Product\ProductFeaturedImages;
use AppCore\Domain\Product\ProductImageGroup;
use AppCore\Domain\Product\ProductMaker;
use AppCore\Domain\Product\ProductMakers;
use AppCore\Domain\Product\ProductNotFoundException;
use AppCore\Domain\Product\ProductPurpose;
use AppCore\Domain\Product\ProductPurposes;
use AppCore\Domain\Product\ProductRepositoryInterface;
use AppCore\Domain\Product\ProductStatus;
use AppCore\Domain\Product\ProductTaxonomies;
use AppCore\Domain\Product\ProductTaxonomy;
use AppCore\Domain\Product\ProductVariant;
use AppCore\Domain\Product\ProductVariants;
use AppCore\Infrastructure\Query\ProductCommandInterface;
use AppCore\Infrastructure\Query\ProductImageCommandInterface;
use AppCore\Infrastructure\Query\ProductImageQueryInterface;
use AppCore\Infrastructure\Query\ProductMakerCommandInterface;
use AppCore\Infrastructure\Query\ProductMakerQueryInterface;
use AppCore\Infrastructure\Query\ProductPropertyCommandInterface;
use AppCore\Infrastructure\Query\ProductPropertyQueryInterface;
use AppCore\Infrastructure\Query\ProductPurposeCommandInterface;
use AppCore\Infrastructure\Query\ProductPurposeQueryInterface;
use AppCore\Infrastructure\Query\ProductQueryInterface;
use AppCore\Infrastructure\Query\ProductTaxonomyCommandInterface;
use AppCore\Infrastructure\Query\ProductTaxonomyQueryInterface;
use AppCore\Infrastructure\Query\ProductVariantCommandInterface;
use AppCore\Infrastructure\Query\ProductVariantQueryInterface;
use DateTimeImmutable;

use function array_map;
use function array_reduce;
use function array_walk;
use function assert;
use function iterator_to_array;

/**
 * @psalm-import-type ProductImageItem from ProductImageQueryInterface
 * @psalm-import-type ProductMakerItem from ProductMakerQueryInterface
 * @psalm-import-type ProductPropertyItem from ProductPropertyQueryInterface
 * @psalm-import-type ProductPurposeItem from ProductPurposeQueryInterface
 * @psalm-import-type ProductItem from ProductQueryInterface
 * @psalm-import-type ProductTaxonomyItem from ProductTaxonomyQueryInterface
 * @psalm-import-type ProductVariantItem from ProductVariantQueryInterface
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class ProductRepository implements ProductRepositoryInterface
{
    /** @SuppressWarnings(PHPMD.ExcessiveParameterList) */
    public function __construct(
        private readonly ProductCommandInterface $productCommandInterface,
        private readonly ProductImageCommandInterface $productImageCommandInterface,
        private readonly ProductImageQueryInterface $productImageQueryInterface,
        private readonly ProductMakerCommandInterface $productMakerCommandInterface,
        private readonly ProductMakerQueryInterface $productMakerQueryInterface,
        private readonly ProductPropertyCommandInterface $productPropertyCommandInterface,
        private readonly ProductPropertyQueryInterface $productPropertyQueryInterface,
        private readonly ProductPurposeCommandInterface $productPurposeCommandInterface,
        private readonly ProductPurposeQueryInterface $productPurposeQueryInterface,
        private readonly ProductQueryInterface $productQueryInterface,
        private readonly ProductTaxonomyCommandInterface $productTaxonomyCommandInterface,
        private readonly ProductTaxonomyQueryInterface $productTaxonomyQueryInterface,
        private readonly ProductVariantCommandInterface $productVariantCommandInterface,
        private readonly ProductVariantQueryInterface $productVariantQueryInterface,
    ) {
    }

    public function findById(int $id): Product
    {
        $productItem = $this->productQueryInterface->item($id);
        if ($productItem === null) {
            throw new ProductNotFoundException();
        }

        $productPropertyItems = $this->productPropertyQueryInterface->listByProductId($id);
        $productVariantItems = $this->productVariantQueryInterface->listByProductId($id);
        $productFeatureImageItems = $this->productImageQueryInterface->listByProductId($id);
        $productMakerItems = $this->productMakerQueryInterface->listByProductId($id);
        $productPurposeItems = $this->productPurposeQueryInterface->listByProductId($id);
        $productTaxonomyItems = $this->productTaxonomyQueryInterface->listByProductId($id);

        return $this->toModel(
            $productItem,
            $productPropertyItems,
            $productVariantItems,
            $productFeatureImageItems,
            $productMakerItems,
            $productPurposeItems,
            $productTaxonomyItems,
        );
    }

    /**
     * @param int<1, max> $storeId
     *
     * @return list<Product>
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function findByStoreId(int $storeId): array
    {
        return $this->findByStoreIdAndProductIds($storeId, []);
    }

    /**
     * @param int<1, max>       $storeId
     * @param list<int<1, max>> $productIds
     *
     * @return list<Product>
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function findByStoreIdAndProductIds(int $storeId, array $productIds): array
    {
        $productItems = empty($productIds) ?
            $this->productQueryInterface->listByStoreId($storeId) :
            $this->productQueryInterface->listByStoreIdAndProductIds($storeId, $productIds);
        if (empty($productItems)) {
            return [];
        }

        $productPropertyItems = $this->productPropertyQueryInterface->listByStoreId($storeId);
        $productPropertyItemMap = array_reduce(
            $productPropertyItems,
            static function (array $carry, array $item) {
                $carry[$item['product_id']][] = $item;

                return $carry;
            },
            [],
        );

        $productVariantItems = $this->productVariantQueryInterface->listByStoreId($storeId);
        $productVariantItemMap = array_reduce(
            $productVariantItems,
            static function (array $carry, array $item) {
                $carry[$item['product_id']][] = $item;

                return $carry;
            },
            [],
        );

        $productFeatureImageItems = $this->productImageQueryInterface->listByStoreId($storeId);
        $productFeatureImageItemMap = array_reduce(
            $productFeatureImageItems,
            static function (array $carry, array $item) {
                $carry[$item['product_id']][] = $item;

                return $carry;
            },
            [],
        );

        $productMakerItems = $this->productMakerQueryInterface->listByStoreId($storeId);
        $productMakerItemMap = array_reduce(
            $productMakerItems,
            static function (array $carry, array $item) {
                $carry[$item['product_id']][] = $item;

                return $carry;
            },
            [],
        );

        $productPurposeItems = $this->productPurposeQueryInterface->listByStoreId($storeId);
        $productPurposeItemMap = array_reduce(
            $productPurposeItems,
            static function (array $carry, array $item) {
                $carry[$item['product_id']][] = $item;

                return $carry;
            },
            [],
        );

        $productTaxonomyItems = $this->productTaxonomyQueryInterface->listByStoreId($storeId);
        $productTaxonomyItemMap = array_reduce(
            $productTaxonomyItems,
            static function (array $carry, array $item) {
                $carry[$item['product_id']][] = $item;

                return $carry;
            },
            [],
        );

        return array_map(
            function (array $productItem) use (
                $productPropertyItemMap,
                $productVariantItemMap,
                $productFeatureImageItemMap,
                $productMakerItemMap,
                $productPurposeItemMap,
                $productTaxonomyItemMap,
            ) {
                $productId = $productItem['id'];
                $productPropertyItems = $productPropertyItemMap[$productId] ?? [];
                $productVariantItems = $productVariantItemMap[$productId] ?? [];
                $productFeatureImageItems = $productFeatureImageItemMap[$productId] ?? [];
                $productMakerItems = $productMakerItemMap[$productId] ?? [];
                $productPurposeItems = $productPurposeItemMap[$productId] ?? [];
                $productTaxonomyItems = $productTaxonomyItemMap[$productId] ?? [];

                return $this->toModel(
                    $productItem,
                    $productPropertyItems,
                    $productVariantItems,
                    $productFeatureImageItems,
                    $productMakerItems,
                    $productPurposeItems,
                    $productTaxonomyItems,
                );
            },
            $productItems,
        );
    }

    public function insert(Product $product): void
    {
        $result = $this->productCommandInterface->add(
            $product->storeId,
            $product->title,
            $product->code,
            (int) $product->taxable,
            $product->taxId,
            $product->originalPrice,
            $product->price,
            $product->numberOfPieces,
            $product->discountRate,
            $product->discountPrice,
            (int) $product->stockistNotificationEnabled,
            $product->stockistName,
            $product->saleStartDate,
            $product->saleEndDate,
            $product->status->value,
        );

        $productId = $result['id'];
        $product->setNewId($productId);

        if ($product->content !== null) {
            $this->insertProductContent($productId, $product->content);
        }

        $this->insertProductVariants($productId, iterator_to_array($product->variants->getIterator(), false));

        if ($product->productFeaturedImages !== null) {
            $this->insertProductFeaturedImages(
                $productId,
                iterator_to_array($product->productFeaturedImages->getIterator(), false),
            );
        }

        if ($product->productMakers !== null) {
            $this->insertProductMakers($productId, iterator_to_array($product->productMakers->getIterator(), false));
        }

        if ($product->productPurposes !== null) {
            $this->insertProductPurposes(
                $productId,
                iterator_to_array($product->productPurposes->getIterator(), false),
            );
        }

        if ($product->productTaxonomies === null) {
            return;
        }

        $this->insertProductTaxonomies(
            $productId,
            iterator_to_array($product->productTaxonomies->getIterator(), false),
        );
    }

    /** @param int<1, max> $productId */
    private function insertProductContent(int $productId, ProductContent $productContent): void
    {
        $props = [
            [ProductPropName::VideoUrl->value, $productContent->videoUrl],
            [ProductPropName::Description->value, $productContent->description],
            [ProductPropName::Volume->value, $productContent->volume],
            [ProductPropName::CountryOfOrigin->value, $productContent->countryOfOrigin],
            [ProductPropName::Specification->value, $productContent->specification],
        ];

        array_walk(
            $props,
            fn (array $item) => $this->productPropertyCommandInterface->add($productId, $item[0], $item[1]),
        );
    }

    /**
     * @param int<1, max>          $productId
     * @param list<ProductVariant> $productVariants
     */
    private function insertProductVariants(int $productId, array $productVariants): void
    {
        array_walk(
            $productVariants,
            function (ProductVariant &$item) use ($productId): void {
                $result = $this->productVariantCommandInterface->add(
                    $productId,
                    $item->title,
                    $item->code,
                    $item->sku,
                    $item->originalPrice,
                    $item->price,
                    $item->discountPrice,
                    $item->pickupDurationDays,
                );

                $item->setNewId($result['id']);
            },
        );
    }

    /**
     * @param int<1, max>                $productId
     * @param list<ProductFeaturedImage> $productFeaturedImages
     */
    private function insertProductFeaturedImages(int $productId, array $productFeaturedImages): void
    {
        array_walk(
            $productFeaturedImages,
            function (ProductFeaturedImage &$item) use ($productId): void {
                $result = $this->productImageCommandInterface->add(
                    $productId,
                    ProductImageGroup::FEATURED->value,
                    $item->size,
                    $item->mediaType,
                    $item->width,
                    $item->height,
                    $item->originalFileName,
                    $item->url,
                    $item->path,
                    $item->fileName,
                    $item->md5,
                    $item->position,
                );

                $item->setNewId($result['id']);
            },
        );
    }

    /**
     * @param int<1, max>        $productId
     * @param list<ProductMaker> $productMakers
     */
    private function insertProductMakers(int $productId, array $productMakers): void
    {
        array_walk(
            $productMakers,
            function (ProductMaker &$item) use ($productId): void {
                $result = $this->productMakerCommandInterface->add(
                    $productId,
                    $item->makerId,
                    $item->position,
                );

                $item->setNewId($result['id']);
            },
        );
    }

    /**
     * @param int<1, max>          $productId
     * @param list<ProductPurpose> $productPurposes
     */
    private function insertProductPurposes(int $productId, array $productPurposes): void
    {
        array_walk(
            $productPurposes,
            function (ProductPurpose &$item) use ($productId): void {
                $result = $this->productPurposeCommandInterface->add(
                    $productId,
                    $item->purposeId,
                    $item->position,
                );

                $item->setNewId($result['id']);
            },
        );
    }

    /**
     * @param int<1, max>           $productId
     * @param list<ProductTaxonomy> $productTaxonomies
     */
    private function insertProductTaxonomies(int $productId, array $productTaxonomies): void
    {
        array_walk(
            $productTaxonomies,
            function (ProductTaxonomy &$item) use ($productId): void {
                $result = $this->productTaxonomyCommandInterface->add(
                    $productId,
                    $item->taxonomyId,
                    $item->position,
                );

                $item->setNewId($result['id']);
            },
        );
    }

    /** @SuppressWarnings(PHPMD.ExcessiveMethodLength) */
    public function update(Product $product): void
    {
        if (empty($product->id)) {
            return;
        }

        $this->productCommandInterface->update(
            $product->id,
            $product->title,
            $product->code,
            (int) $product->taxable,
            $product->taxId,
            $product->originalPrice,
            $product->price,
            $product->numberOfPieces,
            $product->discountRate,
            $product->discountPrice,
            (int) $product->stockistNotificationEnabled,
            $product->stockistName,
            $product->saleStartDate,
            $product->saleEndDate,
            $product->status->value,
        );

        $productId = $product->id;

        if ($product->content !== null) {
            $this->upsertProductContent($productId, $product->content);
        }

        $this->upsertProductVariants($productId, iterator_to_array($product->variants->getIterator(), false));

        if ($product->productFeaturedImages !== null) {
            $this->upsertProductFeaturedImages(
                $productId,
                iterator_to_array($product->productFeaturedImages->getIterator(), false),
            );
        }

        if ($product->productMakers !== null) {
            $this->upsertProductMakers($productId, iterator_to_array($product->productMakers->getIterator(), false));
        }

        if ($product->productPurposes !== null) {
            $this->upsertProductPurposes(
                $productId,
                iterator_to_array($product->productPurposes->getIterator(), false),
            );
        }

        if ($product->productTaxonomies === null) {
            return;
        }

        $this->upsertProductTaxonomies(
            $productId,
            iterator_to_array($product->productTaxonomies->getIterator(), false),
        );
    }

    /** @param int<1, max> $productId */
    private function upsertProductContent(int $productId, ProductContent $productContent): void
    {
        $props = [
            [ProductPropName::VideoUrl->value, $productContent->videoUrl],
            [ProductPropName::Description->value, $productContent->description],
            [ProductPropName::Volume->value, $productContent->volume],
            [ProductPropName::CountryOfOrigin->value, $productContent->countryOfOrigin],
            [ProductPropName::Specification->value, $productContent->specification],
        ];

        array_walk(
            $props,
            function (array $item) use ($productId): void {
                $result = $this->productPropertyCommandInterface->update($productId, $item[0], $item[1]);
                if ($result['row_count'] > 0) {
                    return;
                }

                $this->productPropertyCommandInterface->add($productId, $item[0], $item[1]);
            },
        );
    }

    /**
     * @param int<1, max>          $productId
     * @param list<ProductVariant> $productVariants
     */
    private function upsertProductVariants(int $productId, array $productVariants): void
    {
        $aliveIds = [];

        array_walk(
            $productVariants,
            function (ProductVariant &$item) use ($productId, &$aliveIds): void {
                if (empty($item->id)) {
                    $result = $this->productVariantCommandInterface->add(
                        $productId,
                        $item->title,
                        $item->code,
                        $item->sku,
                        $item->originalPrice,
                        $item->price,
                        $item->discountPrice,
                        $item->pickupDurationDays,
                    );

                    $id = $result['id'];

                    $aliveIds[] = $id;

                    $item->setNewId($id);

                    return;
                }

                $aliveIds[] = $item->id;

                $this->productVariantCommandInterface->update(
                    $item->id,
                    $item->title,
                    $item->code,
                    $item->sku,
                    $item->originalPrice,
                    $item->price,
                    $item->discountPrice,
                    $item->pickupDurationDays,
                );
            },
        );

        $this->productVariantCommandInterface->deleteOld($productId, $aliveIds);
    }

    /**
     * @param int<1, max>                 $productId
     * @param array<ProductFeaturedImage> $productFeaturedImages
     */
    private function upsertProductFeaturedImages(int $productId, array $productFeaturedImages): void
    {
        $aliveIds = [];

        array_walk(
            $productFeaturedImages,
            function (ProductFeaturedImage &$item) use ($productId, &$aliveIds): void {
                if (empty($item->id)) {
                    $result = $this->productImageCommandInterface->add(
                        $productId,
                        ProductImageGroup::FEATURED->value,
                        $item->size,
                        $item->mediaType,
                        $item->width,
                        $item->height,
                        $item->originalFileName,
                        $item->url,
                        $item->path,
                        $item->fileName,
                        $item->md5,
                        $item->position,
                    );

                    $id = $result['id'];

                    $item->setNewId($id);

                    $aliveIds[] = $id;

                    return;
                }

                $aliveIds[] = $item->id;
            },
        );

        $this->productImageCommandInterface->deleteOld($productId, ProductImageGroup::FEATURED->value, $aliveIds);
    }

    /**
     * @param int<1, max>        $productId
     * @param list<ProductMaker> $productMakers
     */
    private function upsertProductMakers(int $productId, array $productMakers): void
    {
        $aliveIds = [];

        array_walk(
            $productMakers,
            function (ProductMaker &$item) use ($productId, &$aliveIds): void {
                if (empty($item->id)) {
                    $result = $this->productMakerCommandInterface->add(
                        $productId,
                        $item->makerId,
                        $item->position,
                    );

                    $id = $result['id'];

                    $item->setNewId($id);

                    $aliveIds[] = $id;

                    return;
                }

                $aliveIds[] = $item->id;

                $this->productMakerCommandInterface->update($item->id, $item->position);
            },
        );

        $this->productMakerCommandInterface->deleteOld($productId, $aliveIds);
    }

    /**
     * @param int<1, max>          $productId
     * @param list<ProductPurpose> $productPurposes
     */
    private function upsertProductPurposes(int $productId, array $productPurposes): void
    {
        $aliveIds = [];

        array_walk(
            $productPurposes,
            function (ProductPurpose &$item) use ($productId, &$aliveIds): void {
                if (empty($item->id)) {
                    $result = $this->productPurposeCommandInterface->add(
                        $productId,
                        $item->purposeId,
                        $item->position,
                    );

                    $id = $result['id'];

                    $item->setNewId($id);

                    $aliveIds[] = $id;

                    return;
                }

                $aliveIds[] = $item->id;

                $this->productPurposeCommandInterface->update($item->id, $item->position);
            },
        );

        $this->productPurposeCommandInterface->deleteOld($productId, $aliveIds);
    }

    /**
     * @param int<1, max>           $productId
     * @param list<ProductTaxonomy> $productTaxonomies
     */
    private function upsertProductTaxonomies(int $productId, array $productTaxonomies): void
    {
        $aliveIds = [];

        array_walk(
            $productTaxonomies,
            function (ProductTaxonomy &$item) use ($productId, &$aliveIds): void {
                if (empty($item->id)) {
                    $result = $this->productTaxonomyCommandInterface->add(
                        $productId,
                        $item->taxonomyId,
                        $item->position,
                    );

                    $id = $result['id'];

                    $item->setNewId($id);

                    $aliveIds[] = $id;

                    return;
                }

                $aliveIds[] = $item->id;

                $this->productTaxonomyCommandInterface->update($item->id, $item->position);
            },
        );

        $this->productTaxonomyCommandInterface->deleteOld($productId, $aliveIds);
    }

    /**
     * @psalm-param ProductItem               $productItem
     * @psalm-param list<ProductPropertyItem> $productPropertyItems
     * @psalm-param list<ProductVariantItem>  $productVariantItems
     * @psalm-param list<ProductImageItem>    $productFeatureImageItems
     * @psalm-param list<ProductMakerItem>    $productMakerItems
     * @psalm-param list<ProductPurposeItem>  $productPurposeItems
     * @psalm-param list<ProductTaxonomyItem> $productTaxonomyItems
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function toModel(
        array $productItem,
        array $productPropertyItems,
        array $productVariantItems,
        array $productFeatureImageItems,
        array $productMakerItems,
        array $productPurposeItems,
        array $productTaxonomyItems,
    ): Product {
        $productId = (int) $productItem['id'];
        assert($productId > 0);

        $storeId = (int) $productItem['store_id'];
        assert($storeId > 0);

        $taxId = empty($productItem['tax_id']) ? null : (int) $productItem['tax_id'];
        assert($taxId === null || $taxId > 0);

        $originalPrice = (int) $productItem['original_price'];
        assert($originalPrice > -1);

        $price = (int) $productItem['price'];
        assert($price > -1);

        $numberOfPieces = (int) $productItem['number_of_pieces'];
        assert($numberOfPieces > 0);

        $discountRate = empty($productItem['discount_rate']) ? null : (int) $productItem['discount_rate'];
        assert($discountRate === null || $discountRate > 0);
        assert($discountRate === null || $discountRate < 101);

        $discountPrice = empty($productItem['discount_price']) ? null : (int) $productItem['discount_price'];
        assert($discountPrice === null || $discountPrice < 1);

        return Product::reconstruct(
            $productId,
            $storeId,
            $productItem['title'],
            $productItem['code'],
            (bool) $productItem['taxable'],
            $taxId,
            $originalPrice,
            $price,
            $numberOfPieces,
            $discountRate,
            $discountPrice,
            (bool) $productItem['stockist_notification_enabled'],
            $productItem['stockist_name'],
            empty($productItem['sale_start_date']) ? null : new DateTimeImmutable($productItem['sale_start_date']),
            empty($productItem['sale_end_date']) ? null : new DateTimeImmutable($productItem['sale_end_date']),
            empty($productPropertyItems) ? null : $this->toProductContent($productPropertyItems),
            $this->toProductVariants($productVariantItems),
            empty($productTaxonomyItems) ? null : $this->toProductTaxonomies($productTaxonomyItems),
            empty($productMakerItems) ? null : $this->toProductMakers($productMakerItems),
            empty($productPurposeItems) ? null : $this->toProductPurposes($productPurposeItems),
            empty($productFeatureImageItems) ? null : $this->toProductFeatureImages($productFeatureImageItems),
            ProductStatus::from($productItem['status']),
        );
    }

    /**
     * @psalm-param list<ProductPropertyItem> $productPropertyItems
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function toProductContent(array $productPropertyItems): ProductContent
    {
        $productPropertyMap = array_reduce(
            $productPropertyItems,
            static function (array $carry, array $item) {
                $carry[$item['name']] = $item['value'];

                return $carry;
            },
            [],
        );

        return ProductContent::reconstruct(
            $productPropertyMap[ProductPropName::VideoUrl->value] ?? '',
            $productPropertyMap[ProductPropName::Description->value] ?? '',
            $productPropertyMap[ProductPropName::Volume->value] ?? '',
            $productPropertyMap[ProductPropName::CountryOfOrigin->value] ?? '',
            $productPropertyMap[ProductPropName::Specification->value] ?? '',
        );
    }

    /**
     * @psalm-param list<ProductVariantItem> $productVariantItems
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function toProductVariants(array $productVariantItems): ProductVariants
    {
        return new ProductVariants(
            array_map(
                static function (array $productVariantItem) {
                    $id = (int) $productVariantItem['id'];
                    assert($id > 0);

                    $originalPrice = (int) $productVariantItem['original_price'];
                    assert($originalPrice > -1);

                    $price = (int) $productVariantItem['price'];
                    assert($price > -1);

                    $discountPrice = empty($productVariantItem['discount_price']) ? null : (int) $productVariantItem['discount_price'];
                    assert($discountPrice === null || $discountPrice < 1);

                    $pickupDurationDays = (int) $productVariantItem['pickup_duration_days'];
                    assert($pickupDurationDays > -1);

                    return ProductVariant::reconstruct(
                        $id,
                        $productVariantItem['title'],
                        $productVariantItem['code'],
                        $productVariantItem['sku'],
                        $originalPrice,
                        $price,
                        $discountPrice,
                        $pickupDurationDays,
                    );
                },
                $productVariantItems,
            ),
        );
    }

    /**
     * @psalm-param list<ProductTaxonomyItem> $productTaxonomyItems
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function toProductTaxonomies(array $productTaxonomyItems): ProductTaxonomies
    {
        return new ProductTaxonomies(
            array_map(
                static function (array $productTaxonomyItem) {
                    $id = (int) $productTaxonomyItem['id'];
                    assert($id > 0);

                    $taxonomyId = (int) $productTaxonomyItem['taxonomy_id'];
                    assert($taxonomyId > 0);

                    $position = (int) $productTaxonomyItem['position'];
                    assert($position > 0);

                    return ProductTaxonomy::reconstruct(
                        $id,
                        $taxonomyId,
                        $position,
                    );
                },
                $productTaxonomyItems,
            ),
        );
    }

    /**
     * @psalm-param list<ProductMakerItem> $productMakerItems
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function toProductMakers(array $productMakerItems): ProductMakers
    {
        return new ProductMakers(
            array_map(
                static function (array $productTaxonomyItem) {
                    $id = (int) $productTaxonomyItem['id'];
                    assert($id > 0);

                    $makerId = (int) $productTaxonomyItem['maker_id'];
                    assert($makerId > 0);

                    $position = (int) $productTaxonomyItem['position'];
                    assert($position > 0);

                    return ProductMaker::reconstruct(
                        $id,
                        $makerId,
                        $position,
                    );
                },
                $productMakerItems,
            ),
        );
    }

    /**
     * @psalm-param list<ProductPurposeItem> $productPurposeItems
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function toProductPurposes(array $productPurposeItems): ProductPurposes
    {
        return new ProductPurposes(
            array_map(
                static function (array $productPurposeItem) {
                    $id = (int) $productPurposeItem['id'];
                    assert($id > 0);

                    $purposeId = (int) $productPurposeItem['purpose_id'];
                    assert($purposeId > 0);

                    $position = (int) $productPurposeItem['position'];
                    assert($position > 0);

                    return ProductPurpose::reconstruct(
                        $id,
                        $purposeId,
                        $position,
                    );
                },
                $productPurposeItems,
            ),
        );
    }

    /**
     * @psalm-param list<ProductImageItem> $productFeatureImageItems
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function toProductFeatureImages(array $productFeatureImageItems): ProductFeaturedImages
    {
        return new ProductFeaturedImages(
            array_map(
                static function (array $productFeatureImageItem) {
                    $id = (int) $productFeatureImageItem['id'];
                    assert($id > 0);

                    $size = (int) $productFeatureImageItem['size'];
                    assert($size > 0);

                    $width = (int) $productFeatureImageItem['width'];
                    assert($width > 0);

                    $height = (int) $productFeatureImageItem['height'];
                    assert($height > 0);

                    $position = (int) $productFeatureImageItem['position'];
                    assert($position > 0);

                    return ProductFeaturedImage::reconstruct(
                        $id,
                        $size,
                        $productFeatureImageItem['media_type'],
                        $width,
                        $height,
                        $productFeatureImageItem['original_file_name'],
                        $productFeatureImageItem['url'],
                        $productFeatureImageItem['path'],
                        $productFeatureImageItem['file_name'],
                        $productFeatureImageItem['md5'],
                        $position,
                    );
                },
                $productFeatureImageItems,
            ),
        );
    }
}
