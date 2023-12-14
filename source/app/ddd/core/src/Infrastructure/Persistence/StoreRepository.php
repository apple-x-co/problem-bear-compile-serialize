<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Store\Store;
use AppCore\Domain\Store\StoreConfigure;
use AppCore\Domain\Store\StoreHero;
use AppCore\Domain\Store\StoreHeroes;
use AppCore\Domain\Store\StoreHeroImage;
use AppCore\Domain\Store\StoreImageGroup;
use AppCore\Domain\Store\StoreLogoImage;
use AppCore\Domain\Store\StoreNotFoundException;
use AppCore\Domain\Store\StoreRepositoryInterface;
use AppCore\Domain\Store\StoreStatus;
use AppCore\Infrastructure\Query\StoreCommandInterface;
use AppCore\Infrastructure\Query\StoreImageCommandInterface;
use AppCore\Infrastructure\Query\StoreImagePropertyCommandInterface;
use AppCore\Infrastructure\Query\StoreImagePropertyQueryInterface;
use AppCore\Infrastructure\Query\StoreImageQueryInterface;
use AppCore\Infrastructure\Query\StorePropertyCommandInterface;
use AppCore\Infrastructure\Query\StorePropertyQueryInterface;
use AppCore\Infrastructure\Query\StoreQueryInterface;
use DateTimeImmutable;

use function array_filter;
use function array_map;
use function array_reduce;
use function array_values;
use function array_walk;
use function assert;
use function iterator_to_array;

/**
 * @psalm-import-type StoreImagePropertyItem from StoreImagePropertyQueryInterface
 * @psalm-import-type StoreImageItem from StoreImageQueryInterface
 * @psalm-import-type StorePropertyItem from StorePropertyQueryInterface
 * @psalm-import-type StoreItem from StoreQueryInterface
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class StoreRepository implements StoreRepositoryInterface
{
    public function __construct(
        private readonly StoreCommandInterface $storeCommand,
        private readonly StoreImageCommandInterface $storeImageCommand,
        private readonly StoreImagePropertyCommandInterface $storeImagePropertyCommand,
        private readonly StoreImagePropertyQueryInterface $storeImagePropertyQuery,
        private readonly StoreImageQueryInterface $storeImageQuery,
        private readonly StorePropertyCommandInterface $storePropertyCommand,
        private readonly StorePropertyQueryInterface $storePropertyQuery,
        private readonly StoreQueryInterface $storeQuery,
    ) {
    }

    public function findById(int $id): Store
    {
        $storeItem = $this->storeQuery->item($id);
        if ($storeItem === null) {
            throw new StoreNotFoundException();
        }

        $storePropertyItems = $this->storePropertyQuery->listByStoreId($id);
        $storeImageItems = $this->storeImageQuery->listByStoreId($id);
        $storeImagePropertyItems = $this->storeImagePropertyQuery->listByStoreId($id);

        return $this->toModel(
            $storeItem,
            $storePropertyItems,
            $storeImageItems,
            $storeImagePropertyItems,
        );
    }

    public function findByKey(string $key): Store
    {
        $storeItem = $this->storeQuery->itemByKey($key);
        if ($storeItem === null) {
            throw new StoreNotFoundException();
        }

        $storeId = (int) $storeItem['id'];
        assert($storeId > 0);

        $storePropertyItems = $this->storePropertyQuery->listByStoreId($storeId);
        $storeImageItems = $this->storeImageQuery->listByStoreId($storeId);
        $storeImagePropertyItems = $this->storeImagePropertyQuery->listByStoreId($storeId);

        return $this->toModel(
            $storeItem,
            $storePropertyItems,
            $storeImageItems,
            $storeImagePropertyItems,
        );
    }

    /**
     * @psalm-param StoreItem                    $storeItem
     * @psalm-param list<StorePropertyItem>      $storePropertyItems
     * @psalm-param list<StoreImageItem>         $storeImageItems
     * @psalm-param list<StoreImagePropertyItem> $storeImagePropertyItems
     */
    private function toModel(
        array $storeItem,
        array $storePropertyItems,
        array $storeImageItems,
        array $storeImagePropertyItems,
    ): Store {
        $id = (int) $storeItem['id'];
        assert($id > 0);

        return Store::reconstruct(
            $id,
            $storeItem['url'],
            $storeItem['key'],
            $storeItem['name'],
            $this->toStoreConfigure($storePropertyItems),
            $this->toStoreLogoImage($storeImageItems),
            $this->toStoreHeroes($storeImageItems, $storeImagePropertyItems),
            StoreStatus::from($storeItem['status']),
            empty($storeItem['leave_date']) ? null : new DateTimeImmutable($storeItem['leave_date']),
            empty($storeItem['void_date']) ? null : new DateTimeImmutable($storeItem['void_date']),
        );
    }

    /** @param list<StorePropertyItem> $storePropertyItems */
    private function toStoreConfigure(array $storePropertyItems): StoreConfigure
    {
        $storePropertyItemMap = array_reduce(
            $storePropertyItems,
            static function (array $carry, array $item) {
                $carry[$item['name']] = $item['value'];

                return $carry;
            },
            [],
        );

        $pointRate = isset($storePropertyItemMap[StorePropName::PointRate->value]) ? (int) $storePropertyItemMap[StorePropName::PointRate->value] : null;
        assert($pointRate === null || $pointRate > 0);
        assert($pointRate === null || $pointRate < 101);

        return StoreConfigure::reconstruct(
            $storePropertyItemMap[StorePropName::PrimaryColor->value] ?? null,
            $storePropertyItemMap[StorePropName::TermText->value] ?? null,
            $storePropertyItemMap[StorePropName::LegalText->value] ?? null,
            $storePropertyItemMap[StorePropName::PrivacyText->value] ?? null,
            $storePropertyItemMap[StorePropName::ContactText->value] ?? null,
            (bool) ($storePropertyItemMap[StorePropName::PointEnabled->value] ?? null),
            $pointRate,
            $storePropertyItemMap[StorePropName::OrderNotificationRecipient->value] ?? null,
        );
    }

    /** @param list<StoreImageItem> $storeImageItems */
    private function toStoreLogoImage(array $storeImageItems): StoreLogoImage|null
    {
        $items = array_filter(
            $storeImageItems,
            static fn (array $item) => $item['group'] === StoreImageGroup::LOGO->value
        );
        if (empty($items)) {
            return null;
        }

        $item = $items[0];

        $id = (int) $item['id'];
        assert($id > 0);

        $size = (int) $item['size'];
        assert($size > 0);

        $width = (int) $item['width'];
        assert($width > 0);

        $height = (int) $item['height'];
        assert($height > 0);

        return StoreLogoImage::reconstruct(
            $id,
            $size,
            $item['media_type'],
            $width,
            $height,
            $item['original_file_name'],
            $item['url'],
            $item['path'],
            $item['file_name'],
            $item['md5'],
        );
    }

    /**
     * @psalm-param list<StoreImageItem>         $storeImageItems
     * @psalm-param list<StoreImagePropertyItem> $storeImagePropertyItems
     */
    private function toStoreHeroes($storeImageItems, $storeImagePropertyItems): StoreHeroes
    {
        $items = array_filter(
            $storeImageItems,
            static fn (array $item) => $item['group'] === StoreImageGroup::HERO->value
        );
        if (empty($items)) {
            return new StoreHeroes([]);
        }

        $storeImagePropertyItemMap = array_reduce(
            $storeImagePropertyItems,
            static function (array $carry, array $item) {
                $carry[$item['name']] = $item['value'];

                return $carry;
            },
            [],
        );

        return new StoreHeroes(
            array_map(
                static function (array $item) use ($storeImagePropertyItemMap) {
                    $id = (int) $item['id'];
                    assert($id > 0);

                    $size = (int) $item['size'];
                    assert($size > 0);

                    $width = (int) $item['width'];
                    assert($width > 0);

                    $height = (int) $item['height'];
                    assert($height > 0);

                    $position = (int) $item['position'];
                    assert($position > 0);

                    return StoreHero::reconstruct(
                        $storeImagePropertyItemMap[StoreImagePropName::Title->value] ?? '',
                        $storeImagePropertyItemMap[StoreImagePropName::Subtitle->value] ?? '',
                        $storeImagePropertyItemMap[StoreImagePropName::LinkUrl->value] ?? '',
                        StoreHeroImage::reconstruct(
                            $id,
                            $size,
                            $item['media_type'],
                            $width,
                            $height,
                            $item['original_file_name'],
                            $item['url'],
                            $item['path'],
                            $item['file_name'],
                            $item['md5'],
                        ),
                        $position,
                    );
                },
                array_values($items),
            ),
        );
    }

    public function insert(Store $store): void
    {
        $result = $this->storeCommand->add(
            $store->url,
            $store->key,
            $store->name,
            $store->status->value,
            $store->leaveDate,
            $store->voidDate,
        );

        $storeId = $result['id'];
        $store->setNewId($storeId);

        $this->insertStoreConfig($storeId, $store->configure);

        $storeLogoImage = $store->logoImage;
        if ($storeLogoImage !== null) {
            $this->insertStoreLogoImage($storeId, $storeLogoImage);
        }

        if ($store->heroes === null) {
            return;
        }

        $this->insertStoreHero($storeId, iterator_to_array($store->heroes->getIterator(), false));
    }

    /** @param int<1, max> $storeId */
    private function insertStoreConfig(int $storeId, StoreConfigure $storeConfigure): void
    {
        $props = [
            [StorePropName::TermText->value, $storeConfigure->termText],
            [StorePropName::PrimaryColor->value, $storeConfigure->primaryColor],
            [StorePropName::LegalText->value, $storeConfigure->legalText],
            [StorePropName::PrivacyText->value, $storeConfigure->privacyText],
            [StorePropName::ContactText->value, $storeConfigure->contactText],
            [StorePropName::PointEnabled->value, (string) $storeConfigure->pointEnabled],
            [StorePropName::PointRate->value, (string) $storeConfigure->pointRate],
            [StorePropName::OrderNotificationRecipient->value, $storeConfigure->orderNotificationRecipient],
        ];

        array_walk(
            $props,
            function (array $item) use ($storeId): void {
                if ($item[1] === null) {
                    return;
                }

                $this->storePropertyCommand->add($storeId, $item[0], $item[1]);
            },
        );
    }

    /** @param int<1, max> $storeId */
    private function insertStoreLogoImage(int $storeId, StoreLogoImage $storeLogoImage): void
    {
        $result = $this->storeImageCommand->add(
            $storeId,
            StoreImageGroup::LOGO->value,
            $storeLogoImage->size,
            $storeLogoImage->mediaType,
            $storeLogoImage->width,
            $storeLogoImage->height,
            $storeLogoImage->originalFileName,
            $storeLogoImage->url,
            $storeLogoImage->path,
            $storeLogoImage->fileName,
            $storeLogoImage->md5,
            1,
        );

        $storeLogoImage->setNewId($result['id']);
    }

    /**
     * @param int<1, max>     $storeId
     * @param list<StoreHero> $storeHeroes
     */
    private function insertStoreHero(int $storeId, array $storeHeroes): void
    {
        $heroImagePosition = 0;

        array_walk(
            $storeHeroes,
            function (StoreHero &$item) use ($storeId, &$heroImagePosition): void {
                $storeHeroImage = $item->image;
                $result = $this->storeImageCommand->add(
                    $storeId,
                    StoreImageGroup::HERO->value,
                    $storeHeroImage->size,
                    $storeHeroImage->mediaType,
                    $storeHeroImage->width,
                    $storeHeroImage->height,
                    $storeHeroImage->originalFileName,
                    $storeHeroImage->url,
                    $storeHeroImage->path,
                    $storeHeroImage->fileName,
                    $storeHeroImage->md5,
                    ++$heroImagePosition,
                );

                $storeImageId = $result['id'];

                $storeHeroImage->setNewId($storeImageId);

                $props = [
                    [StoreImagePropName::Title->value, $item->title],
                    [StoreImagePropName::Subtitle->value, $item->subtitle],
                    [StoreImagePropName::LinkUrl->value, $item->linkUrl],
                ];
                foreach ($props as $prop) {
                    $this->storeImagePropertyCommand->add($storeImageId, $prop[0], $prop[1]);
                }
            },
        );
    }

    public function update(Store $store): void
    {
        if (empty($store->id)) {
            return;
        }

        $this->storeCommand->update(
            $store->id,
            $store->url,
            $store->key,
            $store->name,
            $store->status->value,
            $store->leaveDate,
            $store->voidDate,
        );

        $this->upsertStoreConfig($store->id, $store->configure);

        $storeLogoImage = $store->logoImage;
        if ($storeLogoImage !== null) {
            $this->upsertStoreLogoImage($store->id, $storeLogoImage);
        }

        if ($store->heroes === null) {
            return;
        }

        $this->upsertStoreHeroes($store->id, iterator_to_array($store->heroes->getIterator(), false));
    }

    /** @param int<1, max> $storeId */
    private function upsertStoreConfig(int $storeId, StoreConfigure $storeConfigure): void
    {
        $props = [
            [StorePropName::TermText->value, $storeConfigure->termText],
            [StorePropName::PrimaryColor->value, $storeConfigure->primaryColor],
            [StorePropName::LegalText->value, $storeConfigure->legalText],
            [StorePropName::PrivacyText->value, $storeConfigure->privacyText],
            [StorePropName::ContactText->value, $storeConfigure->contactText],
            [StorePropName::PointEnabled->value, (string) $storeConfigure->pointEnabled],
            [StorePropName::PointRate->value, (string) $storeConfigure->pointRate],
            [StorePropName::OrderNotificationRecipient->value, $storeConfigure->orderNotificationRecipient],
        ];

        array_walk(
            $props,
            function (array $item) use ($storeId): void {
                if ($item[1] === null) {
                    return;
                }

                $result = $this->storePropertyCommand->update($storeId, $item[0], $item[1]);
                if ($result['row_count'] > 0) {
                    return;
                }

                $this->storePropertyCommand->add($storeId, $item[0], $item[1]);
            },
        );
    }

    /** @param int<1, max> $storeId */
    private function upsertStoreLogoImage(int $storeId, StoreLogoImage $storeLogoImage): void
    {
        if (! empty($storeLogoImage->id)) {
            return;
        }

        $result = $this->storeImageCommand->add(
            $storeId,
            StoreImageGroup::LOGO->value,
            $storeLogoImage->size,
            $storeLogoImage->mediaType,
            $storeLogoImage->width,
            $storeLogoImage->height,
            $storeLogoImage->originalFileName,
            $storeLogoImage->url,
            $storeLogoImage->path,
            $storeLogoImage->fileName,
            $storeLogoImage->md5,
            1,
        );

        $storeImageId = $result['id'];

        $storeLogoImage->setNewId($storeImageId);

        $this->storeImageCommand->deleteOld($storeImageId, StoreImageGroup::LOGO->value, [$storeImageId]);
    }

    /**
     * @param int<1, max>     $storeId
     * @param list<StoreHero> $storeHeroes
     */
    private function upsertStoreHeroes(int $storeId, array $storeHeroes): void
    {
        $aliveIds = [];

        $heroImagePosition = 0;

        array_walk(
            $storeHeroes,
            function (StoreHero &$item) use ($storeId, &$heroImagePosition, &$aliveIds): void {
                $storeHeroImage = $item->image;

                $props = [
                    [StoreImagePropName::Title->value, $item->title],
                    [StoreImagePropName::Subtitle->value, $item->subtitle],
                    [StoreImagePropName::LinkUrl->value, $item->linkUrl],
                ];

                if (empty($storeHeroImage->id)) {
                    $result = $this->storeImageCommand->add(
                        $storeId,
                        StoreImageGroup::HERO->value,
                        $storeHeroImage->size,
                        $storeHeroImage->mediaType,
                        $storeHeroImage->width,
                        $storeHeroImage->height,
                        $storeHeroImage->originalFileName,
                        $storeHeroImage->url,
                        $storeHeroImage->path,
                        $storeHeroImage->fileName,
                        $storeHeroImage->md5,
                        ++$heroImagePosition,
                    );

                    $id = $result['id'];

                    $storeHeroImage->setNewId($id);

                    $aliveIds[] = $id;

                    foreach ($props as $prop) {
                        $this->storeImagePropertyCommand->add($id, $prop[0], $prop[1]);
                    }

                    return;
                }

                $this->storeImageCommand->update($storeHeroImage->id, ++$heroImagePosition);

                $aliveIds[] = $storeHeroImage->id;

                foreach ($props as $prop) {
                    $result = $this->storeImagePropertyCommand->update($storeHeroImage->id, $prop[0], $prop[1]);
                    if ($result['row_count'] > 0) {
                        continue;
                    }

                    $this->storeImagePropertyCommand->add($storeHeroImage->id, $prop[0], $prop[1]);
                }
            },
        );

        $this->storeImageCommand->deleteOld($storeId, StoreImageGroup::HERO->value, $aliveIds);
    }
}
