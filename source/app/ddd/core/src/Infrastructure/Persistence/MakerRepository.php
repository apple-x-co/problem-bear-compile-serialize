<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Maker\Maker;
use AppCore\Domain\Maker\MakerNotFoundException;
use AppCore\Domain\Maker\MakerRepositoryInterface;
use AppCore\Infrastructure\Query\MakerCommandInterface;
use AppCore\Infrastructure\Query\MakerQueryInterface;

use function array_map;
use function assert;

/** @psalm-import-type MakerItem from MakerQueryInterface */
class MakerRepository implements MakerRepositoryInterface
{
    public function __construct(
        private readonly MakerCommandInterface $makerCommand,
        private readonly MakerQueryInterface $makerQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): Maker
    {
        $item = $this->makerQuery->item($id);
        if ($item === null) {
            throw  new MakerNotFoundException();
        }

        return $this->toModel($item);
    }

    /**
     * @param int<1, max> $storeId
     *
     * @return list<Maker>
     */
    public function findByStoreId(int $storeId): array
    {
        $items = $this->makerQuery->listByStoreId($storeId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    public function insert(Maker $maker): void
    {
        $result = $this->makerCommand->add(
            $maker->storeId,
            $maker->parentId,
            $maker->name,
            $maker->position,
        );

        $maker->setNewId($result['id']);
    }

    public function update(Maker $maker): void
    {
        if (empty($maker->id)) {
            return;
        }

        $this->makerCommand->update(
            $maker->id,
            $maker->parentId,
            $maker->name,
            $maker->position,
        );
    }

    /**
     * @psalm-param MakerItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    public function toModel(array $item): Maker
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $storeId = (int) $item['store_id'];
        assert($storeId > 0);

        $position = (int) $item['position'];
        assert($position > 0);

        $parentId = empty($item['parent_id']) ? null : (int) $item['parent_id'];
        assert($parentId === null || $parentId > 0);

        return Maker::reconstruct(
            $id,
            $storeId,
            $item['name'],
            $position,
            $parentId,
        );
    }
}
