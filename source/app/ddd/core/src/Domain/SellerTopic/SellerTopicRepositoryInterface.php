<?php

declare(strict_types=1);

namespace AppCore\Domain\SellerTopic;

interface SellerTopicRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): SellerTopic;

    /** @return list<SellerTopic> */
    public function findAll(): array;

    /** @return list<SellerTopic> */
    public function findByPublished(): array;

    public function insert(SellerTopic $sellerTopic): void;

    public function update(SellerTopic $sellerTopic): void;

    public function delete(SellerTopic $sellerTopic): void;
}
