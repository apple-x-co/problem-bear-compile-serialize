<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Taxonomy\Taxonomy;
use AppCore\Domain\Taxonomy\TaxonomyNotFoundException;
use AppCore\Domain\Taxonomy\TaxonomyRepositoryInterface;
use AppCore\Infrastructure\Query\TaxonomyCommandInterface;
use AppCore\Infrastructure\Query\TaxonomyQueryInterface;

use function array_map;
use function assert;

/**
 * @psalm-import-type TaxonomyItem from TaxonomyQueryInterface
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class TaxonomyRepository implements TaxonomyRepositoryInterface
{
    public function __construct(
        private readonly TaxonomyCommandInterface $taxonomyCommand,
        private readonly TaxonomyQueryInterface $taxonomyQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): Taxonomy
    {
        $item = $this->taxonomyQuery->item($id);
        if ($item === null) {
            throw  new TaxonomyNotFoundException();
        }

        return $this->toModel($item);
    }

    /**
     * @param int<1, max> $storeId
     *
     * @return list<Taxonomy>
     */
    public function findByStoreId(int $storeId): array
    {
        $items = $this->taxonomyQuery->listByStoreId($storeId);

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    public function insert(Taxonomy $taxonomy): void
    {
        $result = $this->taxonomyCommand->add(
            $taxonomy->storeId,
            $taxonomy->parentId,
            $taxonomy->name,
            $taxonomy->position,
        );

        $taxonomy->setNewId($result['id']);
    }

    public function update(Taxonomy $taxonomy): void
    {
        if (empty($taxonomy->id)) {
            return;
        }

        $this->taxonomyCommand->update(
            $taxonomy->id,
            $taxonomy->parentId,
            $taxonomy->name,
            $taxonomy->position,
        );
    }

    /** @psalm-param TaxonomyItem $item */
    public function toModel(array $item): Taxonomy
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $storeId = (int) $item['store_id'];
        assert($storeId > 0);

        $position = (int) $item['position'];
        assert($position > 0);

        $parentId = empty($item['parent_id']) ? null : (int) $item['parent_id'];
        assert($parentId === null || $parentId > 0);

        return Taxonomy::reconstruct(
            $id,
            $storeId,
            $item['name'],
            $position,
            $parentId,
        );
    }
}
