<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Persistence;

use AppCore\Domain\Company\Company;
use AppCore\Domain\Company\CompanyContact;
use AppCore\Domain\Company\CompanyNotFoundException;
use AppCore\Domain\Company\CompanyRepositoryInterface;
use AppCore\Domain\Company\CompanyStatus;
use AppCore\Infrastructure\Query\CompanyCommandInterface;
use AppCore\Infrastructure\Query\CompanyPropertyCommandInterface;
use AppCore\Infrastructure\Query\CompanyPropertyQueryInterface;
use AppCore\Infrastructure\Query\CompanyQueryInterface;
use DateTimeImmutable;

use function array_reduce;
use function array_walk;
use function assert;

/**
 * @psalm-import-type CompanyPropertyItem from CompanyPropertyQueryInterface
 * @psalm-import-type CompanyItem from CompanyQueryInterface
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CompanyRepository implements CompanyRepositoryInterface
{
    public function __construct(
        private readonly CompanyCommandInterface $companyCommand,
        private readonly CompanyPropertyCommandInterface $companyPropertyCommand,
        private readonly CompanyPropertyQueryInterface $companyPropertyQuery,
        private readonly CompanyQueryInterface $companyQuery,
    ) {
    }

    /** @param int<1, max> $id */
    public function findById(int $id): Company
    {
        $companyItem = $this->companyQuery->item($id);
        if ($companyItem === null) {
            throw new CompanyNotFoundException((string) $id);
        }

        $companyPropertyItems = $this->companyPropertyQuery->listByCompanyId($id);

        return $this->toModel($companyItem, $companyPropertyItems);
    }

    public function insert(Company $company): void
    {
        $result = $this->companyCommand->add(
            $company->name,
            $company->sellerSlug,
            $company->sellerUrl,
            $company->consumerSlug,
            $company->consumerUrl,
            $company->storeId,
            $company->paymentMethodId,
            $company->status->value,
            $company->leaveDate,
            $company->voidDate,
        );

        $companyId = $result['id'];
        $company->setNewId($companyId);

        $this->insertCompanyContact($companyId, $company->contact);
    }

    /** @param int<1, max> $companyId */
    private function insertCompanyContact(int $companyId, CompanyContact $companyContact): void
    {
        $props = [
            [CompanyPropName::PostalCode->value, $companyContact->postalCode],
            [CompanyPropName::State->value, $companyContact->state],
            [CompanyPropName::City->value, $companyContact->city],
            [CompanyPropName::AddressLine1->value, $companyContact->addressLine1],
            [CompanyPropName::AddressLine2->value, $companyContact->addressLine2],
            [CompanyPropName::PhoneNumber->value, $companyContact->phoneNumber],
            [CompanyPropName::RepresentativeName->value, $companyContact->representativeName],
            [CompanyPropName::RepresentativeEmail->value, $companyContact->representativeEmail],
        ];
        array_walk(
            $props,
            fn (array $item) => $this->companyPropertyCommand->add($companyId, $item[0], $item[1])
        );
    }

    public function update(Company $company): void
    {
        if (empty($company->id)) {
            return;
        }

        $this->companyCommand->update(
            $company->id,
            $company->name,
            $company->sellerSlug,
            $company->sellerUrl,
            $company->consumerSlug,
            $company->consumerUrl,
            $company->storeId,
            $company->paymentMethodId,
            $company->status->value,
            $company->leaveDate,
            $company->voidDate,
        );

        $this->updateCompanyContact($company->id, $company->contact);
    }

    /** @param int<1, max> $companyId */
    private function updateCompanyContact(int $companyId, CompanyContact $companyContact): void
    {
        $props = [
            [CompanyPropName::PostalCode->value, $companyContact->postalCode],
            [CompanyPropName::State->value, $companyContact->state],
            [CompanyPropName::City->value, $companyContact->city],
            [CompanyPropName::AddressLine1->value, $companyContact->addressLine1],
            [CompanyPropName::AddressLine2->value, $companyContact->addressLine2],
            [CompanyPropName::PhoneNumber->value, $companyContact->phoneNumber],
            [CompanyPropName::RepresentativeName->value, $companyContact->representativeName],
            [CompanyPropName::RepresentativeEmail->value, $companyContact->representativeEmail],
        ];
        array_walk(
            $props,
            function (array $item) use ($companyId): void {
                $result = $this->companyPropertyCommand->update($companyId, $item[0], $item[1]);
                if ($result['row_count'] > 0) {
                    return;
                }

                $this->companyPropertyCommand->add($companyId, $item[0], $item[1]);
            },
        );
    }

    /**
     * @psalm-param CompanyItem $item
     * @psalm-param list<CompanyPropertyItem> $propertyItems
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function toModel(array $item, array $propertyItems): Company
    {
        $propertyItemMap = array_reduce(
            $propertyItems,
            static function (array $carry, array $item) {
                $carry[$item['name']] = $item['value'];

                return $carry;
            },
            [],
        );

        $id = (int) $item['id'];
        assert($id > 0);

        $storeId = empty($item['store_id']) ? null : (int) $item['store_id'];
        assert($storeId === null || $storeId > 0);

        $paymentMethodId = empty($item['payment_method_id']) ? null : (int) $item['payment_method_id'];
        assert($paymentMethodId === null || $paymentMethodId > 0);

        return Company::reconstruct(
            $id,
            $item['name'],
            CompanyContact::reconstruct(
                $propertyItemMap[CompanyPropName::PostalCode->value] ?? '',
                $propertyItemMap[CompanyPropName::State->value] ?? '',
                $propertyItemMap[CompanyPropName::City->value] ?? '',
                $propertyItemMap[CompanyPropName::AddressLine1->value] ?? '',
                $propertyItemMap[CompanyPropName::AddressLine2->value] ?? '',
                $propertyItemMap[CompanyPropName::PhoneNumber->value] ?? '',
                $propertyItemMap[CompanyPropName::RepresentativeName->value] ?? '',
                $propertyItemMap[CompanyPropName::RepresentativeEmail->value] ?? '',
            ),
            $item['seller_slug'],
            $item['seller_url'],
            $item['consumer_slug'],
            $item['consumer_url'],
            $storeId,
            $paymentMethodId,
            CompanyStatus::from($item['status']),
            empty($item['leave_date']) ? null : new DateTimeImmutable($item['leave_date']),
            empty($item['void_date']) ? null : new DateTimeImmutable($item['void_date']),
        );
    }
}
