<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\PaymentMethod\PaymentMethod;
use AppCore\Domain\PaymentMethod\PaymentMethodInvalidException;
use AppCore\Domain\PaymentMethod\PaymentMethodNotFoundException;
use AppCore\Domain\PaymentMethod\PaymentMethodRepositoryInterface;
use AppCore\Domain\PaymentMethod\PaymentMethodRole;

use function array_map;

class SellerPaymentMethodRepository extends PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    public function findById(int $id): PaymentMethod
    {
        $paymentMethod = parent::findById($id);
        if ($paymentMethod->role !== PaymentMethodRole::SELLER) {
            throw new PaymentMethodNotFoundException();
        }

        return $paymentMethod;
    }

    /** @return list<PaymentMethod> */
    public function findList(): array
    {
        $items = $this->paymentMethodQuery->listForSeller();
        if (empty($items)) {
            throw new PaymentMethodNotFoundException();
        }

        return array_map(
            fn (array $item) => $this->toModel($item),
            $items,
        );
    }

    public function insert(PaymentMethod $paymentMethod): void
    {
        if ($paymentMethod->role !== PaymentMethodRole::SELLER) {
            throw new PaymentMethodInvalidException();
        }

        parent::insert($paymentMethod);
    }

    public function update(PaymentMethod $paymentMethod): void
    {
        if ($paymentMethod->role !== PaymentMethodRole::SELLER) {
            throw new PaymentMethodInvalidException();
        }

        parent::insert($paymentMethod);
    }
}
