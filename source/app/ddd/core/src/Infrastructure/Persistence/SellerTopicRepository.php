<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\SellerTopic\SellerTopic;
use AppCore\Domain\SellerTopic\SellerTopicNotFoundException;
use AppCore\Domain\SellerTopic\SellerTopicRepositoryInterface;
use AppCore\Infrastructure\Query\SellerTopicCommandInterface;
use AppCore\Infrastructure\Query\SellerTopicQueryInterface;
use DateTimeImmutable;

use function array_map;
use function assert;

/** @psalm-import-type SellerTopicItem from SellerTopicQueryInterface */
class SellerTopicRepository implements SellerTopicRepositoryInterface
{
    public function __construct(
        private readonly SellerTopicCommandInterface $sellerTopicCommand,
        private readonly SellerTopicQueryInterface $sellerTopicQuery,
    ) {
    }

    public function findById(int $id): SellerTopic
    {
        $item = $this->sellerTopicQuery->item($id);
        if ($item === null) {
            throw new SellerTopicNotFoundException();
        }

        return $this->toModel($item);
    }

    /** @return list<SellerTopic> */
    public function findAll(): array
    {
        $items = $this->sellerTopicQuery->list();

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    /** @return list<SellerTopic> */
    public function findByPublished(): array
    {
        $items = $this->sellerTopicQuery->listByPublished();

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    /**
     * @psalm-param SellerTopicItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): SellerTopic
    {
        $id = (int) $item['id'];
        assert($id > 0);

        return SellerTopic::reconstruct(
            $id,
            new DateTimeImmutable($item['publish_start_date']),
            empty($item['publish_end_date']) ? null : new DateTimeImmutable($item['publish_end_date']),
            $item['title'],
            $item['text'],
        );
    }

    public function insert(SellerTopic $sellerTopic): void
    {
        $result = $this->sellerTopicCommand->add(
            $sellerTopic->publishStartDate,
            $sellerTopic->publishEndDate,
            $sellerTopic->title,
            $sellerTopic->text,
        );

        $sellerTopic->setNewId($result['id']);
    }

    public function update(SellerTopic $sellerTopic): void
    {
        if (empty($sellerTopic->id)) {
            return;
        }

        $this->sellerTopicCommand->update(
            $sellerTopic->id,
            $sellerTopic->publishStartDate,
            $sellerTopic->publishEndDate,
            $sellerTopic->title,
            $sellerTopic->text,
        );
    }

    public function delete(SellerTopic $sellerTopic): void
    {
        if (empty($sellerTopic->id)) {
            return;
        }

        $this->sellerTopicCommand->delete($sellerTopic->id);
    }
}
