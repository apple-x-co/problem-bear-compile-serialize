<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\CustomerReward\CustomerPoint;
use AppCore\Domain\CustomerReward\CustomerPoints;
use AppCore\Domain\CustomerReward\CustomerReward;
use AppCore\Domain\CustomerReward\CustomerRewardNotFoundException;
use AppCore\Domain\CustomerReward\CustomerRewardRepositoryInterface;
use AppCore\Domain\CustomerReward\PointType;
use AppCore\Infrastructure\Query\CustomerPointCommandInterface;
use AppCore\Infrastructure\Query\CustomerPointQueryInterface;
use AppCore\Infrastructure\Query\CustomerRewardCommandInterface;
use AppCore\Infrastructure\Query\CustomerRewardQueryInterface;
use DateTimeImmutable;

use function array_map;
use function array_walk;
use function assert;
use function iterator_to_array;

/**
 * @psalm-import-type CustomerRewardItem from CustomerRewardQueryInterface
 * @psalm-import-type CustomerPointItem from CustomerPointQueryInterface
 */
class CustomerRewardRepository implements CustomerRewardRepositoryInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly CustomerPointCommandInterface $customerPointCommand,
        private readonly CustomerPointQueryInterface $customerPointQuery,
        private readonly CustomerRewardCommandInterface $customerRewardCommand,
        private readonly CustomerRewardQueryInterface $customerRewardQuery,
    ) {
    }

    public function findByUniqueKey(int $customerId, int $storeId): CustomerReward
    {
        $rewardItem = $this->customerRewardQuery->itemByUniqueKey($customerId, $storeId);
        if ($rewardItem === null) {
            throw new CustomerRewardNotFoundException();
        }

        $id = (int) $rewardItem['id'];
        assert($id > 0);

        $pointItems = $this->customerPointQuery->listByCustomerRewardId($id);

        return $this->toModel($rewardItem, $pointItems);
    }

    public function insert(CustomerReward $customerReward): void
    {
        $result = $this->customerRewardCommand->add(
            $customerReward->customerId,
            $customerReward->storeId,
            $customerReward->remainingPoint,
        );

        $customerRewardId = $result['id'];
        $customerReward->setNewId($customerRewardId);

        $points = iterator_to_array($customerReward->points->getIterator(), false);
        $this->insertCustomerPoints($customerRewardId, $points);
    }

    /**
     * @param int<1, max>         $customerRewardId
     * @param list<CustomerPoint> $customerPoints
     */
    public function insertCustomerPoints(int $customerRewardId, array $customerPoints): void
    {
        array_walk(
            $customerPoints,
            function (CustomerPoint $item) use ($customerRewardId): void {
                $this->customerPointCommand->add(
                    $customerRewardId,
                    $item->uuid,
                    $item->type->value,
                    $item->transactionDate,
                    $item->expireDate,
                    $item->point,
                    $item->remainingPoint,
                    $item->invalidationDate,
                    $item->invalidationReason,
                );
            },
        );
    }

    public function update(CustomerReward $customerReward): void
    {
        if (empty($customerReward->id)) {
            return;
        }

        $this->customerRewardCommand->update(
            $customerReward->id,
            $customerReward->remainingPoint,
        );

        $customerRewardId = $customerReward->id;

        $points = iterator_to_array($customerReward->points->getIterator(), false);
        array_walk(
            $points,
            function (CustomerPoint $item) use ($customerRewardId): void {
                $id = $item->id;

                if (empty($id)) {
                    $this->customerPointCommand->add(
                        $customerRewardId,
                        $item->uuid,
                        $item->type->value,
                        $item->transactionDate,
                        $item->expireDate,
                        $item->point,
                        $item->remainingPoint,
                        $item->invalidationDate,
                        $item->invalidationReason,
                    );

                    return;
                }

                $this->customerPointCommand->update(
                    $id,
                    $item->remainingPoint,
                    $item->invalidationDate,
                    $item->invalidationReason,
                );
            },
        );
    }

    /**
     * @psalm-param CustomerRewardItem      $rewardItem
     * @psalm-param list<CustomerPointItem> $pointItems
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $rewardItem, array $pointItems): CustomerReward
    {
        $points = array_map(
            static function (array $item) {
                $id = (int) $item['id'];
                assert($id > 0);

                $point = (int) $item['point'];
                assert($point > 0);

                $remainingPoint = (int) $item['remaining_point'];
                assert($remainingPoint >= 0);

                return CustomerPoint::reconstruct(
                    $id,
                    $item['uuid'],
                    PointType::from($item['type']),
                    new DateTimeImmutable($item['transaction_date']),
                    new DateTimeImmutable($item['expire_date']),
                    $point,
                    $remainingPoint,
                    empty($item['invalidation_date']) ? null : new DateTimeImmutable($item['invalidation_date']),
                    empty($item['invalidation_reason']) ? null : $item['invalidation_reason'],
                );
            },
            $pointItems,
        );

        $id = (int) $rewardItem['id'];
        assert($id > 0);

        $customerId = (int) $rewardItem['customer_id'];
        assert($customerId > 0);

        $storeId = (int) $rewardItem['store_id'];
        assert($storeId > 0);

        $remainingPoint = (int) $rewardItem['remaining_point'];
        assert($remainingPoint >= 0);

        return CustomerReward::reconstruct(
            $id,
            $customerId,
            $storeId,
            $remainingPoint,
            new CustomerPoints($points),
        );
    }
}
