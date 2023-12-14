<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Order\Billing;
use AppCore\Domain\Order\BillingNotFoundException;
use AppCore\Domain\Order\Order;
use AppCore\Domain\Order\OrderLineItem;
use AppCore\Domain\Order\OrderLineItemNotFoundException;
use AppCore\Domain\Order\OrderLineItems;
use AppCore\Domain\Order\OrderNotFoundException;
use AppCore\Domain\Order\OrderPickup;
use AppCore\Domain\Order\OrderRepositoryInterface;
use AppCore\Domain\Order\OrderStatus;
use AppCore\Domain\Order\PickUpStatus;
use AppCore\Infrastructure\Query\BillingCommandInterface;
use AppCore\Infrastructure\Query\BillingQueryInterface;
use AppCore\Infrastructure\Query\OrderCommandInterface;
use AppCore\Infrastructure\Query\OrderLineItemCommandInterface;
use AppCore\Infrastructure\Query\OrderLineItemQueryInterface;
use AppCore\Infrastructure\Query\OrderPickupCommandInterface;
use AppCore\Infrastructure\Query\OrderPickupQueryInterface;
use AppCore\Infrastructure\Query\OrderQueryInterface;
use DateTimeImmutable;

use function array_map;
use function array_walk;
use function assert;
use function iterator_to_array;

/**
 * @psalm-import-type BillingItem from BillingQueryInterface
 * @psalm-import-type OrderItem from OrderQueryInterface
 * @psalm-import-type OrderLineItemItem from OrderLineItemQueryInterface
 * @psalm-import-type OrderPickupItem from OrderPickupQueryInterface
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        public readonly BillingCommandInterface $billingCommand,
        public readonly BillingQueryInterface $billingQuery,
        public readonly OrderCommandInterface $orderCommand,
        public readonly OrderLineItemCommandInterface $orderLineItemCommand,
        public readonly OrderLineItemQueryInterface $orderLineItemQuery,
        public readonly OrderPickupCommandInterface $orderPickupCommand,
        public readonly OrderPickupQueryInterface $orderPickupQuery,
        public readonly OrderQueryInterface $orderQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): Order
    {
        $orderItem = $this->orderQuery->findById($id);
        if ($orderItem === null) {
            throw new OrderNotFoundException();
        }

        $billingItem = $this->billingQuery->itemByOrderId($id);
        if ($billingItem === null) {
            throw new BillingNotFoundException();
        }

        $orderLineItemItems = $this->orderLineItemQuery->listByOrderId($id);
        if (empty($orderLineItemItems)) {
            throw new OrderLineItemNotFoundException();
        }

        $orderPickupItem = $this->orderPickupQuery->itemByOrderId($id);

        return $this->toModel($orderItem, $billingItem, $orderLineItemItems, $orderPickupItem);
    }

    public function insert(Order $order): void
    {
        $result = $this->orderCommand->add(
            $order->storeId,
            $order->orderNo,
            $order->orderDate,
            $order->closeDate,
            $order->familyName,
            $order->givenName,
            $order->phoneticFamilyName,
            $order->phoneticGivenName,
            $order->postalCode,
            $order->state,
            $order->city,
            $order->addressLine1,
            $order->addressLine2,
            $order->phoneNumber,
            $order->email,
            $order->discountCode,
            $order->discountPrice,
            $order->pointRate,
            $order->spendingPoint,
            $order->earningPoint,
            $order->totalPrice,
            $order->totalTax,
            $order->subtotalPrice,
            $order->paymentMethodId,
            $order->paymentMethodName,
            $order->paymentFee,
            $order->note,
            $order->orderNote,
            $order->pickUpStatus->value,
            $order->status->value,
        );

        $orderId = $result['id'];
        $order->setNewId($orderId);

        $this->insertBilling($orderId, $order->billing);

        if ($order->orderPickup !== null) {
            $this->insertOrderPickup($orderId, $order->orderPickup);
        }

        $orderLineItems = iterator_to_array($order->orderLineItems->getIterator(), false);
        $this->insertOrderLineItem($orderId, $orderLineItems);
    }

    /** @param int<1, max> $orderId */
    private function insertBilling(int $orderId, Billing $billing): void
    {
        $this->billingCommand->add(
            $orderId,
            $billing->billingNo,
            $billing->chargeAmount,
            $billing->billingDate,
            $billing->familyName,
            $billing->givenName,
            $billing->phoneticFamilyName,
            $billing->phoneticGivenName,
            $billing->postalCode,
            $billing->state,
            $billing->city,
            $billing->addressLine1,
            $billing->addressLine2,
            $billing->phoneNumber,
            $billing->email,
        );
    }

    /** @param int<1, max> $orderId */
    private function insertOrderPickup(int $orderId, OrderPickup $orderPickup): void
    {
        $this->orderPickupCommand->add(
            $orderId,
            $orderPickup->pickupDate,
            $orderPickup->pickupTime,
            $orderPickup->shopId,
            $orderPickup->shopName,
            $orderPickup->staffMemberId,
            $orderPickup->staffMemberName,
        );
    }

    /**
     * @param int<1, max>         $orderId
     * @param list<OrderLineItem> $orderLineItems
     */
    private function insertOrderLineItem(int $orderId, array $orderLineItems): void
    {
        array_walk(
            $orderLineItems,
            function (OrderLineItem $item) use ($orderId): void {
                $this->orderLineItemCommand->add(
                    $orderId,
                    $item->productId,
                    $item->productVariantId,
                    $item->title,
                    $item->makerName,
                    $item->taxonomyName,
                    $item->discountPrice,
                    $item->originalPrice,
                    $item->originalTax,
                    $item->originalLinePrice,
                    $item->finalPrice,
                    $item->finalTax,
                    $item->finalLinePrice,
                    $item->taxRate,
                    $item->quantity,
                );
            },
        );
    }

    public function update(Order $order): void
    {
        if (empty($order->id)) {
            return;
        }

        $this->orderCommand->update(
            $order->id,
            $order->closeDate,
            $order->note,
            $order->orderNote,
            $order->pickUpStatus->value,
            $order->status->value,
        );
    }

    /**
     * @psalm-param OrderItem               $orderItem
     * @psalm-param BillingItem             $billingItem
     * @psalm-param list<OrderLineItemItem> $orderLineItemItems
     * @psalm-param OrderPickupItem|null    $orderPickupItem
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(
        array $orderItem,
        array $billingItem,
        array $orderLineItemItems,
        array|null $orderPickupItem,
    ): Order {
        $orderId = (int) $orderItem['id'];
        assert($orderId > 0);

        $storeId = (int) $orderItem['store_id'];
        assert($storeId > 0);

        $discountPrice = empty($orderItem['discount_price']) ? null : (int) $orderItem['discount_price'];
        assert($discountPrice === null || $discountPrice < 0);

        $pointRate = empty($orderItem['point_rate']) ? null : (int) $orderItem['point_rate'];
        assert($pointRate === null || $pointRate > 0);
        assert($pointRate === null || $pointRate < 101);

        $spendingPoint = empty($orderItem['spending_point']) ? null : (int) $orderItem['spending_point'];
        assert($spendingPoint === null || $spendingPoint < 1);

        $earningPoint = empty($orderItem['earning_point']) ? null : (int) $orderItem['earning_point'];
        assert($earningPoint === null || $earningPoint > -1);

        $totalPrice = (int) $orderItem['total_price'];
        assert($totalPrice > 0);

        $totalTax = (int) $orderItem['total_tax'];
        assert($totalTax > 0);

        $subtotalPrice = (int) $orderItem['subtotal_price'];
        assert($subtotalPrice > 0);

        $paymentMethodId = (int) $orderItem['payment_method_id'];
        assert($paymentMethodId > 0);

        $paymentFee = (int) $orderItem['payment_fee'];
        assert($paymentFee > 0);

        return Order::reconstruct(
            $orderId,
            $storeId,
            $orderItem['order_no'],
            new DateTimeImmutable($orderItem['order_date']),
            empty($orderItem['close_date']) ? null : new DateTimeImmutable($orderItem['close_date']),
            $orderItem['family_name'],
            $orderItem['given_name'],
            $orderItem['phonetic_family_name'],
            $orderItem['phonetic_given_name'],
            $orderItem['postal_code'],
            $orderItem['state'],
            $orderItem['city'],
            $orderItem['address_line_1'],
            $orderItem['address_line_2'],
            $orderItem['phone_number'],
            $orderItem['email'],
            $orderItem['discount_code'],
            $discountPrice,
            $pointRate,
            $spendingPoint,
            $earningPoint,
            $totalPrice,
            $totalTax,
            $subtotalPrice,
            $paymentMethodId,
            $orderItem['payment_method_name'],
            $paymentFee,
            $orderItem['note'],
            $orderItem['order_note'],
            PickUpStatus::from($orderItem['pickup_status']),
            OrderStatus::from($orderItem['status']),
            $this->toOrderLineItems($orderLineItemItems),
            $this->toBilling($billingItem),
            empty($orderPickupItem) ? null : $this->toOrderPickup($orderPickupItem),
        );
    }

    /**
     * @psalm-param OrderPickupItem $orderPickupItem
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toOrderPickup(array $orderPickupItem): OrderPickup
    {
        $orderPickupId = (int) $orderPickupItem['id'];
        assert($orderPickupId > 0);

        $shopId = (int) $orderPickupItem['shop_id'];
        assert($shopId > 0);

        $staffMemberId = (int) $orderPickupItem['staff_member_id'];
        assert($staffMemberId > 0);

        return OrderPickup::reconstruct(
            $orderPickupId,
            new DateTimeImmutable($orderPickupItem['pickup_date']),
            $orderPickupItem['pickup_time'],
            $shopId,
            $orderPickupItem['shop_name'],
            $staffMemberId,
            $orderPickupItem['staff_member_name'],
        );
    }

    /**
     * @psalm-param list<OrderLineItemItem> $orderLineItemItems
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toOrderLineItems(array $orderLineItemItems): OrderLineItems
    {
        return new OrderLineItems(
            array_map(
                static function (array $item) {
                    $id = (int) $item['id'];
                    assert($id > 0);

                    $productId = (int) $item['product_id'];
                    assert($productId > 0);

                    $productVariantId = (int) $item['product_variant_id'];
                    assert($productVariantId > 0);

                    $discountPrice = empty($item['discount_price']) ? null : (int) $item['discount_price'];
                    assert($discountPrice === null || $discountPrice <= 0);

                    $originalPrice = (int) $item['original_price'];
                    assert($originalPrice >= 0);

                    $originalTax = (int) $item['original_tax'];
                    assert($originalTax >= 0);

                    $originalLinePrice = (int) $item['original_line_price'];
                    assert($originalLinePrice >= 0);

                    $finalPrice = (int) $item['final_price'];
                    assert($finalPrice >= 0);

                    $finalTax = (int) $item['final_tax'];
                    assert($finalTax >= 0);

                    $finalLinePrice = (int) $item['final_line_price'];
                    assert($finalLinePrice >= 0);

                    $taxRate = (int) $item['tax_rate'];
                    assert($taxRate >= 0);

                    $quantity = (int) $item['quantity'];
                    assert($quantity > 0);

                    return OrderLineItem::reconstruct(
                        $id,
                        $productId,
                        $productVariantId,
                        $item['title'],
                        $item['maker_name'],
                        $item['taxonomy_name'],
                        $discountPrice,
                        $originalPrice,
                        $originalTax,
                        $originalLinePrice,
                        $finalPrice,
                        $finalTax,
                        $finalLinePrice,
                        $taxRate,
                        $quantity,
                    );
                },
                $orderLineItemItems,
            ),
        );
    }

    /**
     * @psalm-param BillingItem $billingItem
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toBilling(array $billingItem): Billing
    {
        $billingId = (int) $billingItem['id'];
        assert($billingId > 0);

        $chargeAmount = (int) $billingItem['charge_amount'];
        assert($chargeAmount > -1);

        return Billing::reconstruct(
            $billingId,
            $billingItem['billing_no'],
            $chargeAmount,
            new DateTimeImmutable($billingItem['billing_date']),
            $billingItem['family_name'],
            $billingItem['given_name'],
            $billingItem['phonetic_family_name'],
            $billingItem['phonetic_given_name'],
            $billingItem['postal_code'],
            $billingItem['state'],
            $billingItem['city'],
            $billingItem['address_line_1'],
            $billingItem['address_line_2'],
            $billingItem['phone_number'],
            $billingItem['email'],
        );
    }
}
