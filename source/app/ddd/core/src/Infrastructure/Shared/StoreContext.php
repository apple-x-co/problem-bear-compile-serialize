<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Store\Store;
use AppCore\Domain\Store\StoreRepositoryInterface;
use AppCore\Exception\RuntimeException;
use AppCore\Presentation\Shared\StoreContextInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function preg_match;

class StoreContext implements StoreContextInterface
{
    private Store|null $store = null;

    public function __construct(
        private readonly ServerRequestInterface $request,
        private readonly StoreRepositoryInterface $storeRepository,
    ) {
    }

    private function getStore(): Store
    {
        if ($this->store !== null) {
            return $this->store;
        }

        $matches = null;
        if (preg_match('/~([0-9a-zA-Z]{10,20})\//', $this->request->getUri()->getPath(), $matches) !== 1) {
            throw new RuntimeException('Site key not found in request URI'); // compile error になる
        }

        $this->store = $this->storeRepository->findByKey($matches[1]);

        return $this->store;
    }

    /** @return int<1, max> */
    public function getStoreId(): int
    {
        $id = $this->getStore()->id;
        assert($id > 0);

        return $id;
    }

    public function getStoreUrl(): string
    {
        return $this->getStore()->url;
    }

    public function getStoreKey(): string
    {
        return $this->getStore()->key;
    }

    public function getStoreName(): string
    {
        return $this->getStore()->name;
    }
}
