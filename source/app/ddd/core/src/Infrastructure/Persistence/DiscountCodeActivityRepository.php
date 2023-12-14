<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\DiscountCodeActivity\DiscountCodeActivity;
use AppCore\Domain\DiscountCodeActivity\DiscountCodeActivityRepositoryInterface;
use AppCore\Infrastructure\Query\DiscountCodeActivityCommandInterface;
use AppCore\Infrastructure\Query\DiscountCodeActivityQueryInterface;
use DateTimeImmutable;

use function array_map;
use function assert;

/** @psalm-import-type DiscountCodeActivityItem from DiscountCodeActivityQueryInterface */
class DiscountCodeActivityRepository implements DiscountCodeActivityRepositoryInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly DiscountCodeActivityCommandInterface $discountCodeActivityCommand,
        private readonly DiscountCodeActivityQueryInterface $discountCodeActivityQuery,
    ) {
    }

    /**
     * @param int<1, max>      $storeId
     * @param int<1, max>|null $customerId
     *
     * @return list<DiscountCodeActivity>
     */
    public function findByStoreCustomerCode(
        int $storeId,
        int|null $customerId,
        string $mail,
        string $phoneNumber,
        string $code,
    ): array {
        $items = $this->discountCodeActivityQuery->listByStoreCustomerCode(
            $storeId,
            $customerId,
            $mail,
            $phoneNumber,
            $code,
        );

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    public function add(DiscountCodeActivity $discountCodeActivity): void
    {
        $result = $this->discountCodeActivityCommand->add(
            $discountCodeActivity->storeId,
            $discountCodeActivity->code,
            $discountCodeActivity->customerId,
            $discountCodeActivity->email,
            $discountCodeActivity->phoneNumber,
            $discountCodeActivity->usedDate,
        );

        $discountCodeActivity->setNewId($result['id']);
    }

    /**
     * @psalm-param DiscountCodeActivityItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): DiscountCodeActivity
    {
        $id = (int) $item['id'];
        assert($id > 0);

        $storeId = (int) $item['store_id'];
        assert($storeId > 0);

        $customerId = empty($item['customer_id']) ? null : (int) $item['customer_id'];
        assert($customerId === null || $customerId > 0);

        return DiscountCodeActivity::reconstruct(
            $id,
            $storeId,
            $item['code'],
            $customerId,
            $item['email'],
            $item['phone_number'],
            new DateTimeImmutable($item['used_date']),
        );
    }
}
