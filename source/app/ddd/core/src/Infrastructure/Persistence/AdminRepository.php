<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Admin\Admin;
use AppCore\Domain\Admin\AdminNotFoundException;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Infrastructure\Query\AdminCommandInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;

use function assert;

/** @psalm-import-type AdminItem from AdminQueryInterface */
class AdminRepository implements AdminRepositoryInterface
{
    public function __construct(
        private readonly AdminCommandInterface $adminCommand,
        private readonly AdminQueryInterface $adminQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): Admin
    {
        $item = $this->adminQuery->item($id);
        if ($item === null) {
            throw new AdminNotFoundException((string) $id);
        }

        return $this->toModel($item);
    }

    public function insert(Admin $admin): void
    {
        $result = $this->adminCommand->add(
            $admin->name,
            $admin->username,
            $admin->password,
        );

        $admin->setNewId($result['id']);
    }

    public function update(Admin $admin): void
    {
        if (empty($admin->id)) {
            return;
        }

        $this->adminCommand->update(
            $admin->id,
            $admin->name,
            $admin->username,
            $admin->password,
        );
    }

    /**
     * @psalm-param AdminItem $item
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    // phpcs:ignore Squiz.Commenting.FunctionComment.MissingParamName
    private function toModel(array $item): Admin
    {
        $id = (int) $item['id'];
        assert($id > 0);

        return Admin::reconstruct(
            $id,
            $item['name'],
            $item['username'],
            $item['password'],
        );
    }
}
