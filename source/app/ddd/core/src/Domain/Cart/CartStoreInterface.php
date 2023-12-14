<?php

declare(strict_types=1);

namespace AppCore\Domain\Cart;

interface CartStoreInterface
{
    public function get(): Cart;

    public function update(Cart $cart): void;

    public function delete(Cart $cart): void;
}
