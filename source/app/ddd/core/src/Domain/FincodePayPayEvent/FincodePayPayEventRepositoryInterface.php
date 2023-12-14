<?php

declare(strict_types=1);

namespace AppCore\Domain\FincodePayPayEvent;

interface FincodePayPayEventRepositoryInterface
{
    public function findByAccessId(string $accessId): FincodePayPayEvent;

    public function insert(FincodePayPayEvent $fincodePayPayEvent): void;

    public function update(FincodePayPayEvent $fincodePayPayEvent): void;
}
