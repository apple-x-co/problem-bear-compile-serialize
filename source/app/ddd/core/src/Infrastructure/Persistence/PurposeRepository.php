<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Purpose\Purpose;
use AppCore\Domain\Purpose\PurposeNotFoundException;
use AppCore\Domain\Purpose\PurposeRepositoryInterface;
use AppCore\Infrastructure\Query\PurposeCommandInterface;
use AppCore\Infrastructure\Query\PurposeQueryInterface;

use function array_map;
use function assert;

/** @psalm-import-type PurposeItem from PurposeQueryInterface */
class PurposeRepository implements PurposeRepositoryInterface
{
    public function __construct(
        private readonly PurposeCommandInterface $purposeCommand,
        private readonly PurposeQueryInterface $purposeQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): Purpose
    {
        $item = $this->purposeQuery->item($id);
        if ($item === null) {
            throw  new PurposeNotFoundException();
        }

        return $this->toModel($item);
    }

    /**
     * @param int<1, max> $storeId
     *
     * @return list<Purpose>
     */
    public function findByStoreId(int $storeId): array
    {
        $items = $this->purposeQuery->listByStoreId($storeId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    public function insert(Purpose $purpose): void
    {
        $result = $this->purposeCommand->add(
            $purpose->storeId,
            $purpose->parentId,
            $purpose->name,
            $purpose->position,
        );

        $purpose->setNewId($result['id']);
    }

    public function update(Purpose $purpose): void
    {
        if (empty($purpose->id)) {
            return;
        }

        $this->purposeCommand->update(
            $purpose->id,
            $purpose->parentId,
            $purpose->name,
            $purpose->position,
        );
    }

    /**
     * @psalm-param PurposeItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    public function toModel(array $item): Purpose
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $storeId = (int) $item['store_id'];
        assert($storeId > 0);

        $position = (int) $item['position'];
        assert($position > 0);

        $parentId = empty($item['parent_id']) ? null : (int) $item['parent_id'];
        assert($parentId === null || $parentId > 0);

        return Purpose::reconstruct(
            $id,
            $storeId,
            $item['name'],
            $position,
            $parentId,
        );
    }
}
