<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\DiscountCode\DiscountCode;
use AppCore\Domain\DiscountCode\DiscountCodeNotFoundException;
use AppCore\Domain\DiscountCode\DiscountCodeRepositoryInterface;
use AppCore\Domain\DiscountCode\DiscountCodeStatus;
use AppCore\Domain\DiscountCode\DiscountEntitledProduct;
use AppCore\Domain\DiscountCode\DiscountEntitledProducts;
use AppCore\Domain\DiscountCode\DiscountTargetSelection;
use AppCore\Domain\DiscountCode\DiscountValueType;
use AppCore\Infrastructure\Query\DiscountCodeCommandInterface;
use AppCore\Infrastructure\Query\DiscountCodeQueryInterface;
use AppCore\Infrastructure\Query\DiscountEntitledProductCommandInterface;
use AppCore\Infrastructure\Query\DiscountEntitledProductQueryInterface;
use DateTimeImmutable;

use function array_map;
use function array_walk;
use function assert;
use function iterator_to_array;

/**
 * @psalm-import-type DiscountCodeItem from DiscountCodeQueryInterface
 * @psalm-import-type DiscountEntitledProductItem from DiscountEntitledProductQueryInterface
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class DiscountCodeRepository implements DiscountCodeRepositoryInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly DiscountCodeCommandInterface $discountCodeCommand,
        private readonly DiscountCodeQueryInterface $discountCodeQuery,
        private readonly DiscountEntitledProductCommandInterface $discountEntitledProductCommand,
        private readonly DiscountEntitledProductQueryInterface $discountEntitledProductQuery,
    ) {
    }

    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function findByUniqueKey(int $storeId, string $code): DiscountCode
    {
        $discountCodeItem = $this->discountCodeQuery->itemUniqueKey($storeId, $code);
        if ($discountCodeItem === null) {
            throw new DiscountCodeNotFoundException();
        }

        $id = (int) $discountCodeItem['id'];
        assert($id > 0);

        $discountEntitledProductItems = $this->discountEntitledProductQuery->listByDiscountCodeId($id);

        return $this->toModel($discountCodeItem, $discountEntitledProductItems);
    }

    public function insert(DiscountCode $discountCode): void
    {
        $result = $this->discountCodeCommand->add(
            $discountCode->storeId,
            $discountCode->title,
            $discountCode->code,
            $discountCode->valueType->value,
            $discountCode->value,
            $discountCode->startDate,
            $discountCode->endDate,
            $discountCode->usageCount,
            $discountCode->usageLimit,
            $discountCode->minimumPrice,
            (int) $discountCode->oncePerCustomer,
            $discountCode->targetSelection->value,
            $discountCode->status->value,
        );

        $discountCodeId = $result['id'];
        $discountCode->setNewId($discountCodeId);

        if ($discountCode->entitledProducts === null) {
            return;
        }

        $entitledProducts = iterator_to_array($discountCode->entitledProducts->getIterator(), false);
        $this->insertDiscountEntitledProducts($discountCodeId, $entitledProducts);
    }

    /**
     * @param int<1, max>                   $discountCodeId
     * @param list<DiscountEntitledProduct> $discountEntitledProducts
     */
    private function insertDiscountEntitledProducts(int $discountCodeId, array $discountEntitledProducts): void
    {
        array_walk(
            $discountEntitledProducts,
            function (DiscountEntitledProduct $item) use ($discountCodeId): void {
                $this->discountEntitledProductCommand->add(
                    $discountCodeId,
                    $item->productId,
                );
            },
        );
    }

    public function update(DiscountCode $discountCode): void
    {
        if (empty($discountCode->id)) {
            return;
        }

        $this->discountCodeCommand->update(
            $discountCode->id,
            $discountCode->title,
            $discountCode->code,
            $discountCode->valueType->value,
            $discountCode->value,
            $discountCode->startDate,
            $discountCode->endDate,
            $discountCode->usageCount,
            $discountCode->usageLimit,
            $discountCode->minimumPrice,
            (int) $discountCode->oncePerCustomer,
            $discountCode->targetSelection->value,
            $discountCode->status->value,
        );

        $aliveIds = [];
        if (! empty($discountCode->entitledProducts)) {
            foreach ($discountCode->entitledProducts as $entitledProduct) {
                if (empty($entitledProduct->id)) {
                    $result = $this->discountEntitledProductCommand->add(
                        $discountCode->id,
                        $entitledProduct->productId,
                    );

                    $aliveIds[] = $result['id'];
                    continue;
                }

                $aliveIds[] = $entitledProduct->id;
            }
        }

        $this->discountEntitledProductCommand->deleteOld($discountCode->id, $aliveIds);
    }

    /**
     * @psalm-param DiscountCodeItem                  $discountCodeItem
     * @psalm-param list<DiscountEntitledProductItem> $discountEntitledProductItems
     */
    public function toModel(array $discountCodeItem, array $discountEntitledProductItems): DiscountCode
    {
        $id = (int) $discountCodeItem['id'];
        assert($id > 0);

        $storeId = (int) $discountCodeItem['store_id'];
        assert($storeId > 0);

        $value = (int) $discountCodeItem['value'];
        assert($value > 0);

        $usageCount = (int) $discountCodeItem['usage_count'];
        assert($usageCount > 0);

        $usageLimit = empty($discountCodeItem['usage_limit']) ? null : (int) $discountCodeItem['usage_limit'];
        assert($usageLimit === null || $usageLimit >= 0);

        $minimumPrice = empty($discountCodeItem['minimum_price']) ? null : (int) $discountCodeItem['minimum_price'];
        assert($minimumPrice === null || $minimumPrice >= 0);

        $entitledProducts = null;
        if (! empty($discountEntitledProductItems)) {
            $entitledProducts = new DiscountEntitledProducts(
                array_map(
                    static function (array $item) {
                        $id = (int) $item['id'];
                        assert($id > 0);

                        $productId = (int) $item['product_id'];
                        assert($productId > 0);

                        return DiscountEntitledProduct::reconstruct($id, $productId);
                    },
                    $discountEntitledProductItems,
                ),
            );
        }

        return DiscountCode::reconstruct(
            $id,
            $storeId,
            $discountCodeItem['title'],
            $discountCodeItem['code'],
            DiscountValueType::from($discountCodeItem['type']),
            $value,
            empty($discountCodeItem['start_date']) ? null : new DateTimeImmutable($discountCodeItem['start_date']),
            empty($discountCodeItem['end_date']) ? null : new DateTimeImmutable($discountCodeItem['end_date']),
            $usageCount,
            $usageLimit,
            $minimumPrice,
            (bool) $discountCodeItem['once_per_customer'],
            DiscountTargetSelection::from($discountCodeItem['target_selection']),
            $entitledProducts,
            DiscountCodeStatus::from($discountCodeItem['status']),
        );
    }
}
