<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\DayOfWeek;
use AppCore\Domain\GeometryLocation;
use AppCore\Domain\Shop\Shop;
use AppCore\Domain\Shop\ShopContact;
use AppCore\Domain\Shop\ShopExteriorImage;
use AppCore\Domain\Shop\ShopHoliday;
use AppCore\Domain\Shop\ShopHolidays;
use AppCore\Domain\Shop\ShopImageGroup;
use AppCore\Domain\Shop\ShopImageNotFoundException;
use AppCore\Domain\Shop\ShopNotFoundException;
use AppCore\Domain\Shop\ShopRegularHoliday;
use AppCore\Domain\Shop\ShopRegularHolidays;
use AppCore\Domain\Shop\ShopRepositoryInterface;
use AppCore\Infrastructure\Query\ShopCommandInterface;
use AppCore\Infrastructure\Query\ShopHolidayCommandInterface;
use AppCore\Infrastructure\Query\ShopHolidayQueryInterface;
use AppCore\Infrastructure\Query\ShopImageCommandInterface;
use AppCore\Infrastructure\Query\ShopImageQueryInterface;
use AppCore\Infrastructure\Query\ShopPropertyCommandInterface;
use AppCore\Infrastructure\Query\ShopPropertyQueryInterface;
use AppCore\Infrastructure\Query\ShopQueryInterface;
use AppCore\Infrastructure\Query\ShopRegularHolidayCommandInterface;
use AppCore\Infrastructure\Query\ShopRegularHolidayQueryInterface;
use DateTimeImmutable;

use function array_map;
use function array_reduce;
use function array_walk;
use function assert;
use function iterator_to_array;

/**
 * @psalm-import-type ShopHolidayItem from ShopHolidayQueryInterface
 * @psalm-import-type ShopImageItem from ShopImageQueryInterface
 * @psalm-import-type ShopPropertyItem from ShopPropertyQueryInterface
 * @psalm-import-type ShopItem from ShopQueryInterface
 * @psalm-import-type ShopRegularHolidayItem from ShopRegularHolidayQueryInterface
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ShopRepository implements ShopRepositoryInterface
{
    public function __construct(
        private readonly ShopCommandInterface $shopCommand,
        private readonly ShopHolidayCommandInterface $shopHolidayCommand,
        private readonly ShopHolidayQueryInterface $shopHolidayQuery,
        private readonly ShopImageCommandInterface $shopImageCommand,
        private readonly ShopImageQueryInterface $shopImageQuery,
        private readonly ShopPropertyCommandInterface $shopPropertyCommand,
        private readonly ShopPropertyQueryInterface $shopPropertyQuery,
        private readonly ShopQueryInterface $shopQuery,
        private readonly ShopRegularHolidayCommandInterface $shopRegularHolidayCommand,
        private readonly ShopRegularHolidayQueryInterface $shopRegularHolidayQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): Shop
    {
        $shopItem = $this->shopQuery->item($id);
        if ($shopItem === null) {
            throw new ShopNotFoundException();
        }

        $shopImageItem = $this->shopImageQuery->itemByShopId($id);
        if ($shopImageItem === null) {
            throw new ShopImageNotFoundException();
        }

        $shopHolidayItems = $this->shopHolidayQuery->listByShopId($id);
        $shopPropertyItems = $this->shopPropertyQuery->listByShopId($id);
        $shopRegularHolidayItems = $this->shopRegularHolidayQuery->listByShopId($id);

        return $this->toModel(
            $shopItem,
            $shopHolidayItems,
            $shopImageItem,
            $shopPropertyItems,
            $shopRegularHolidayItems,
        );
    }

    /**
     * @param int<1, max> $companyId
     *
     * @return list<Shop>
     */
    public function findByCompanyId(int $companyId): array
    {
        $shopItems = $this->shopQuery->listByCompanyId($companyId);

        $shopImageItems = $this->shopImageQuery->listByCompanyId($companyId);
        $shopImageItemMap = array_reduce(
            $shopImageItems,
            static function (array $carry, array $item) {
                $carry[(int) $item['shop_id']] = $item;

                return $carry;
            },
            [],
        );

        $shopHolidayItems = $this->shopHolidayQuery->listByCompanyId($companyId);
        $shopHolidayItemMap = array_reduce(
            $shopHolidayItems,
            static function (array $carry, array $item) {
                $carry[(int) $item['shop_id']][] = $item;

                return $carry;
            },
            [],
        );

        $shopPropertyItems = $this->shopPropertyQuery->listByCompanyId($companyId);
        $shopPropertyItemMap = array_reduce(
            $shopPropertyItems,
            static function (array $carry, array $item) {
                $carry[(int) $item['shop_id']][] = $item;

                return $carry;
            },
            [],
        );

        $shopRegularHolidayItems = $this->shopRegularHolidayQuery->listByCompanyId($companyId);
        $shopRegularHolidayItemMap = array_reduce(
            $shopRegularHolidayItems,
            static function (array $carry, array $item) {
                $carry[(int) $item['shop_id']][] = $item;

                return $carry;
            },
            [],
        );

        return array_map(
            function (array $shopItem) use (
                $shopImageItemMap,
                $shopHolidayItemMap,
                $shopPropertyItemMap,
                $shopRegularHolidayItemMap,
            ) {
                $shopId = (int) $shopItem['id'];

                $shopImageItem = $shopImageItemMap[$shopId] ?? [];
                $shopHolidayItems = $shopHolidayItemMap[$shopId] ?? [];
                $shopPropertyItems = $shopPropertyItemMap[$shopId] ?? [];
                $shopRegularHolidayItems = $shopRegularHolidayItemMap[$shopId] ?? [];

                return $this->toModel(
                    $shopItem,
                    $shopHolidayItems,
                    $shopImageItem,
                    $shopPropertyItems,
                    $shopRegularHolidayItems,
                );
            },
            $shopItems,
        );
    }

    /**
     * @psalm-param ShopItem                     $shopItem
     * @psalm-param list<ShopHolidayItem>        $shopHolidayItems
     * @psalm-param ShopImageItem                $shopImageItem
     * @psalm-param list<ShopPropertyItem>       $shopPropertyItems
     * @psalm-param list<ShopRegularHolidayItem> $shopRegularHolidayItems
     */
    private function toModel(
        array $shopItem,
        array $shopHolidayItems,
        array $shopImageItem,
        array $shopPropertyItems,
        array $shopRegularHolidayItems,
    ): Shop {
        $id = (int) $shopItem['id'];
        assert($id > 0);

        $companyId = (int) $shopItem['company_id'];
        assert($companyId > 0);

        $areaId = (int) $shopItem['area_id'];
        assert($areaId > 0);

        $position = (int) $shopItem['position'];
        assert($position > 0);

        return Shop::reconstruct(
            $id,
            $companyId,
            $areaId,
            $shopItem['name'],
            $this->toShopContact($shopPropertyItems),
            $position,
            $this->toShopExteriorImage($shopImageItem),
            $this->toGeometryLocation($shopPropertyItems),
            empty($shopHolidayItems) ? null : $this->toShopHolidays($shopHolidayItems),
            empty($shopRegularHolidayItems) ? null : $this->toShopRegularHolidays($shopRegularHolidayItems),
        );
    }

    /** @param list<ShopPropertyItem> $shopPropertyItems */
    private function toShopContact(array $shopPropertyItems): ShopContact
    {
        $shopPropertyItemMap = array_reduce(
            $shopPropertyItems,
            static function (array $carry, array $item) {
                $carry[$item['name']] = $item['value'];

                return $carry;
            },
            [],
        );

        return ShopContact::reconstruct(
            $shopPropertyItemMap[ShopPropName::PostalCode->value] ?? '',
            $shopPropertyItemMap[ShopPropName::State->value] ?? '',
            $shopPropertyItemMap[ShopPropName::City->value] ?? '',
            $shopPropertyItemMap[ShopPropName::AddressLine1->value] ?? '',
            $shopPropertyItemMap[ShopPropName::AddressLine2->value] ?? '',
            $shopPropertyItemMap[ShopPropName::PhoneNumber->value] ?? '',
        );
    }

    /** @psalm-param ShopImageItem $shopImageItem */
    private function toShopExteriorImage(array $shopImageItem): ShopExteriorImage
    {
        $id = (int) $shopImageItem['id'];
        assert($id > 0);

        $size = (int) $shopImageItem['size'];
        assert($size > 0);

        $width = (int) $shopImageItem['width'];
        assert($width > 0);

        $height = (int) $shopImageItem['height'];
        assert($height > 0);

        return ShopExteriorImage::reconstruct(
            $id,
            $size,
            $shopImageItem['media_type'],
            $width,
            $height,
            $shopImageItem['original_file_name'],
            $shopImageItem['url'],
            $shopImageItem['path'],
            $shopImageItem['file_name'],
            $shopImageItem['md5'],
        );
    }

    /** @param list<ShopPropertyItem> $shopPropertyItems */
    private function toGeometryLocation(array $shopPropertyItems): GeometryLocation|null
    {
        $shopPropertyItemMap = array_reduce(
            $shopPropertyItems,
            static function (array $carry, array $item) {
                $carry[$item['name']] = $item['value'];

                return $carry;
            },
            [],
        );

        $latitude = $shopPropertyItemMap[ShopPropName::Latitude->value] ?? '0.0';
        $longitude = $shopPropertyItemMap[ShopPropName::Latitude->value] ?? '0.0';

        if ($latitude === '0.0' || $longitude === '0.0') {
            return null;
        }

        return new GeometryLocation((float) $latitude, (float) $longitude);
    }

    /** @param list<ShopHolidayItem> $shopHolidayItems */
    private function toShopHolidays(array $shopHolidayItems): ShopHolidays
    {
        return new ShopHolidays(
            array_map(
                static function (array $shopHolidayItems) {
                    $id = (int) $shopHolidayItems['id'];
                    assert($id > 0);

                    return ShopHoliday::reconstruct(
                        $id,
                        $shopHolidayItems['name'],
                        new DateTimeImmutable($shopHolidayItems['date']),
                    );
                },
                $shopHolidayItems,
            ),
        );
    }

    /** @param list<ShopRegularHolidayItem> $shopRegularHolidayItems */
    private function toShopRegularHolidays(array $shopRegularHolidayItems): ShopRegularHolidays
    {
        return new ShopRegularHolidays(
            array_map(
                static function (array $shopRegularHolidayItem) {
                    $id = (int) $shopRegularHolidayItem['id'];
                    assert($id > 0);

                    return ShopRegularHoliday::reconstruct(
                        $id,
                        DayOfWeek::from($shopRegularHolidayItem['day_of_week']),
                    );
                },
                $shopRegularHolidayItems,
            ),
        );
    }

    public function insert(Shop $shop): void
    {
        $result = $this->shopCommand->add(
            $shop->companyId,
            $shop->areaId,
            $shop->name,
            $shop->position,
        );

        $shopId = $result['id'];
        $shop->setNewId($shopId);

        if ($shop->holidays !== null) {
            $this->insertShopHolidays($shopId, iterator_to_array($shop->holidays->getIterator(), false));
        }

        $this->insertShopExteriorImage($shopId, $shop->exteriorImage);

        $this->insertShopContact($shopId, $shop->contact);

        $geometryLocation = $shop->geometryLocation;
        if ($geometryLocation !== null) {
            $this->insertGeometryLocation($shopId, $geometryLocation);
        }

        if ($shop->regularHolidays === null) {
            return;
        }

        $this->insertShopRegularHoliday($shopId, iterator_to_array($shop->regularHolidays->getIterator(), false));
    }

    /**
     * @param int<1, max>       $shopId
     * @param list<ShopHoliday> $shopHolidays
     */
    private function insertShopHolidays(int $shopId, array $shopHolidays): void
    {
        array_walk(
            $shopHolidays,
            function (ShopHoliday &$item) use ($shopId): void {
                $result = $this->shopHolidayCommand->add(
                    $shopId,
                    $item->date,
                );

                $item->setNewId($result['id']);
            },
        );
    }

    /** @param int<1, max> $shopId */
    private function insertShopExteriorImage(int $shopId, ShopExteriorImage $shopExteriorImage): void
    {
        $result = $this->shopImageCommand->add(
            $shopId,
            ShopImageGroup::EXTERIOR->value,
            $shopExteriorImage->size,
            $shopExteriorImage->mediaType,
            $shopExteriorImage->width,
            $shopExteriorImage->height,
            $shopExteriorImage->originalFileName,
            $shopExteriorImage->url,
            $shopExteriorImage->path,
            $shopExteriorImage->fileName,
            $shopExteriorImage->md5,
        );

        $shopExteriorImage->setNewId($result['id']);
    }

    /** @param int<1, max> $shopId */
    private function insertShopContact(int $shopId, ShopContact $shopContact): void
    {
        $props = [
            [ShopPropName::PostalCode->value, $shopContact->postalCode],
            [ShopPropName::State->value, $shopContact->state],
            [ShopPropName::City->value, $shopContact->city],
            [ShopPropName::AddressLine1->value, $shopContact->addressLine1],
            [ShopPropName::AddressLine2->value, $shopContact->addressLine2],
            [ShopPropName::PhoneNumber->value, $shopContact->phoneNumber],
        ];

        array_walk(
            $props,
            fn (array $item) => $this->shopPropertyCommand->add($shopId, $item[0], $item[1]),
        );
    }

    /** @param int<1, max> $shopId */
    private function insertGeometryLocation(int $shopId, GeometryLocation $geometryLocation): void
    {
        $props = [
            [ShopPropName::Latitude->value, (string) $geometryLocation->latitude],
            [ShopPropName::Longitude->value, (string) $geometryLocation->longitude],
        ];

        array_walk(
            $props,
            fn (array $item) => $this->shopPropertyCommand->add($shopId, $item[0], $item[1]),
        );
    }

    /**
     * @param int<1, max>              $shopId
     * @param list<ShopRegularHoliday> $shopRegularHolidays
     */
    private function insertShopRegularHoliday(int $shopId, array $shopRegularHolidays): void
    {
        array_walk(
            $shopRegularHolidays,
            function (ShopRegularHoliday &$item) use ($shopId): void {
                $result = $this->shopRegularHolidayCommand->add(
                    $shopId,
                    $item->dayOfWeek->value,
                );

                $item->setNewId($result['id']);
            },
        );
    }

    public function update(Shop $shop): void
    {
        if (empty($shop->id)) {
            return;
        }

        $this->shopCommand->update(
            $shop->id,
            $shop->areaId,
            $shop->name,
            $shop->position,
        );

        if ($shop->holidays !== null) {
            $this->upsertShopHolidays($shop->id, iterator_to_array($shop->holidays->getIterator(), false));
        }

        $this->upsertShopExteriorImage($shop->id, $shop->exteriorImage);

        $this->upsertShopContact($shop->id, $shop->contact);

        $geometryLocation = $shop->geometryLocation;
        if ($geometryLocation !== null) {
            $this->upsertGeometryLocation($shop->id, $geometryLocation);
        }

        if ($shop->regularHolidays === null) {
            return;
        }

        $this->upsertShopRegularHoliday($shop->id, iterator_to_array($shop->regularHolidays->getIterator(), false));
    }

    /**
     * @param int<1, max>       $shopId
     * @param list<ShopHoliday> $shopHolidays
     */
    private function upsertShopHolidays(int $shopId, array $shopHolidays): void
    {
        $aliveIds = [];

        array_walk(
            $shopHolidays,
            function (ShopHoliday &$item) use ($shopId, &$aliveIds): void {
                if (empty($item->id)) {
                    $result = $this->shopHolidayCommand->add(
                        $shopId,
                        $item->date,
                    );

                    $id = $result['id'];

                    $item->setNewId($id);

                    $aliveIds[] = $id;

                    return;
                }

                $aliveIds[] = $item->id;
            },
        );

        $this->shopHolidayCommand->deleteOld($shopId, $aliveIds);
    }

    /** @param int<1, max> $shopId */
    private function upsertShopExteriorImage(int $shopId, ShopExteriorImage $shopExteriorImage): void
    {
        if (! empty($shopExteriorImage->id)) {
            return;
        }

        $result = $this->shopImageCommand->add(
            $shopId,
            ShopImageGroup::EXTERIOR->value,
            $shopExteriorImage->size,
            $shopExteriorImage->mediaType,
            $shopExteriorImage->width,
            $shopExteriorImage->height,
            $shopExteriorImage->originalFileName,
            $shopExteriorImage->url,
            $shopExteriorImage->path,
            $shopExteriorImage->fileName,
            $shopExteriorImage->md5,
        );

        $shopImageId = $result['id'];

        $shopExteriorImage->setNewId($shopImageId);

        $this->shopImageCommand->deleteOld($shopId, ShopImageGroup::EXTERIOR->value, [$shopImageId]);
    }

    /** @param int<1, max> $shopId */
    private function upsertShopContact(int $shopId, ShopContact $shopContact): void
    {
        $props = [
            [ShopPropName::PostalCode->value, $shopContact->postalCode],
            [ShopPropName::State->value, $shopContact->state],
            [ShopPropName::City->value, $shopContact->city],
            [ShopPropName::AddressLine1->value, $shopContact->addressLine1],
            [ShopPropName::AddressLine2->value, $shopContact->addressLine2],
            [ShopPropName::PhoneNumber->value, $shopContact->phoneNumber],
        ];

        array_walk(
            $props,
            function (array $item) use ($shopId): void {
                $result = $this->shopPropertyCommand->update($shopId, $item[0], $item[1]);
                if ($result['row_count'] > 0) {
                    return;
                }

                $this->shopPropertyCommand->add($shopId, $item[0], $item[1]);
            },
        );
    }

    /** @param int<1, max> $shopId */
    private function upsertGeometryLocation(int $shopId, GeometryLocation $geometryLocation): void
    {
        $props = [
            [ShopPropName::Latitude->value, (string) $geometryLocation->latitude],
            [ShopPropName::Longitude->value, (string) $geometryLocation->longitude],
        ];

        array_walk(
            $props,
            function (array $item) use ($shopId): void {
                $result = $this->shopPropertyCommand->update($shopId, $item[0], $item[1]);
                if ($result['row_count'] > 0) {
                    return;
                }

                $this->shopPropertyCommand->add($shopId, $item[0], $item[1]);
            },
        );
    }

    /**
     * @param int<1, max>              $shopId
     * @param list<ShopRegularHoliday> $shopRegularHolidays
     */
    private function upsertShopRegularHoliday(int $shopId, array $shopRegularHolidays): void
    {
        $aliveIds = [];

        array_walk(
            $shopRegularHolidays,
            function (ShopRegularHoliday &$item) use ($shopId, &$aliveIds): void {
                if (empty($item->id)) {
                    $result = $this->shopRegularHolidayCommand->add(
                        $shopId,
                        $item->dayOfWeek->value,
                    );

                    $id = $result['id'];

                    $item->setNewId($id);

                    $aliveIds[] = $id;

                    return;
                }

                $aliveIds[] = $item->id;
            },
        );

        $this->shopRegularHolidayCommand->deleteOld($shopId, $aliveIds);
    }
}
