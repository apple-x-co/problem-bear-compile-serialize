<?php

declare(strict_types=1);

namespace AppCore\Domain\PaymentMethod;

interface PaymentMethodRepositoryInterface
{
    /** @param int<1, max> $id */
    public function findById(int $id): PaymentMethod;

    /** @return list<PaymentMethod> */
    public function findList(): array;

    public function insert(PaymentMethod $paymentMethod): void;

    public function update(PaymentMethod $paymentMethod): void;
}
