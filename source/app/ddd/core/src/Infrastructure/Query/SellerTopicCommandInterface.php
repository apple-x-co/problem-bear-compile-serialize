<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface SellerTopicCommandInterface
{
    /** @return array{id: int<1, max>} */
    #[DbQuery('seller_topic_add', type: 'row')]
    public function add(
        DateTimeImmutable $publishStartDate,
        DateTimeImmutable|null $publishEndDate,
        string $title,
        string $text,
    ): array;

    /** @param int<1, max> $id */
    #[DbQuery('seller_topic_update', type: 'row')]
    public function update(
        int $id,
        DateTimeImmutable $publishStartDate,
        DateTimeImmutable|null $publishEndDate,
        string $title,
        string $text,
    ): void;

    /** @param int<1, max> $id */
    #[DbQuery('seller_topic_delete', type: 'row')]
    public function delete(int $id): void;
}
