<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Tax\Tax;
use AppCore\Domain\Tax\TaxNotFoundException;
use AppCore\Domain\Tax\TaxRepositoryInterface;
use AppCore\Infrastructure\Query\TaxCommandInterface;
use AppCore\Infrastructure\Query\TaxQueryInterface;

use function array_map;
use function assert;

/**
 * @psalm-import-type TaxItem from TaxQueryInterface
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class TaxRepository implements TaxRepositoryInterface
{
    public function __construct(
        private readonly TaxCommandInterface $taxCommand,
        private readonly TaxQueryInterface $taxQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): Tax
    {
        $taxItem = $this->taxQuery->item($id);
        if ($taxItem === null) {
            throw new TaxNotFoundException();
        }

        return $this->toModel($taxItem);
    }

    /** @return list<Tax> */
    public function findAll(): array
    {
        $taxItems = $this->taxQuery->list();

        return array_map(
            fn (array $taxItem) => $this->toModel($taxItem),
            $taxItems,
        );
    }

    /** @psalm-param TaxItem $taxItem */
    private function toModel(array $taxItem): Tax
    {
        $id = (int) $taxItem['id'];
        assert($id > 0);

        $rate = (int) $taxItem['rate'];
        assert($rate > 0 && $rate < 101);

        return Tax::reconstruct(
            $id,
            $taxItem['name'],
            $rate,
        );
    }

    public function insert(Tax $tax): void
    {
        $result = $this->taxCommand->add(
            $tax->name,
            $tax->rate,
        );

        $tax->setNewId($result['id']);
    }

    public function update(Tax $tax): void
    {
        if (empty($tax->id)) {
            return;
        }

        $this->taxCommand->update(
            $tax->id,
            $tax->name,
            $tax->rate,
        );
    }
}
