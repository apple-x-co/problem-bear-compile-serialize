<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

/**
 * @psalm-type SellerTopicItem = array{
 *     id: string,
 *     publish_start_date: string,
 *     publish_end_date: string,
 *     title: string,
 *     text: string,
 * }
 */
interface SellerTopicQueryInterface
{
    /**
     * @param int<1, max> $id
     *
     * @return SellerTopicItem|null
     */
    #[DbQuery('seller_topic_item', type: 'row')]
    public function item(int $id): array|null;

    /** @return list<SellerTopicItem> */
    #[DbQuery('seller_topic_list')]
    public function list(): array;

    /** @return list<SellerTopicItem> */
    #[DbQuery('seller_topic_list_by_published')]
    public function listByPublished(): array;
}
