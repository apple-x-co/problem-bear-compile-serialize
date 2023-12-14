<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Area\Area;
use AppCore\Domain\Area\AreaNotFoundException;
use AppCore\Domain\Area\AreaRepositoryInterface;
use AppCore\Infrastructure\Query\AreaCommandInterface;
use AppCore\Infrastructure\Query\AreaQueryInterface;

use function assert;

/** @psalm-import-type AreaItem from AreaQueryInterface */
class AreaRepository implements AreaRepositoryInterface
{
    public function __construct(
        private readonly AreaCommandInterface $areaCommand,
        private readonly AreaQueryInterface $areaQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): Area
    {
        $item = $this->areaQuery->item($id);
        if ($item === null) {
            throw new AreaNotFoundException((string) $id);
        }

        return $this->toModel($item);
    }

    public function insert(Area $area): void
    {
        $result = $this->areaCommand->add(
            $area->companyId,
            $area->name,
            $area->position,
        );

        $area->setNewId($result['id']);
    }

    public function update(Area $area): void
    {
        if (empty($area->id)) {
            return;
        }

        $this->areaCommand->update(
            $area->id,
            $area->name,
            $area->position,
        );
    }

    /**
     * @psalm-param AreaItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function toModel(array $item): Area
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $companyId = (int) $item['company_id'];
        assert($companyId > 0);

        $position = (int) $item['position'];
        assert($position > 0);

        return Area::reconstruct(
            $id,
            $companyId,
            $item['name'],
            $position,
        );
    }
}
