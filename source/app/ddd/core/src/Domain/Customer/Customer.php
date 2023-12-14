<?php

declare(strict_types=1);

namespace AppCore\Domain\Customer;

use AppCore\Domain\GenderType;
use DateTimeImmutable;

final class Customer
{
    private const ID0 = 0;

    /** @var int<1, max>|null */
    private int|null $newId = null;

    /**
     * @param int<0, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public readonly string $familyName,
        public readonly string $givenName,
        public readonly string $phoneticFamilyName,
        public readonly string $phoneticGivenName,
        public readonly GenderType $genderType,
        public readonly string $phoneNumber,
        public readonly string|null $email,
        public readonly string $password,
        public readonly DateTimeImmutable $joinedDate,
        public readonly CustomerStatus $status = CustomerStatus::TEMPORARY,
        public readonly int $id = self::ID0,
    ) {
    }

    /**
     * @param int<1, max> $id
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public static function reconstruct(
        int $id,
        string $familyName,
        string $givenName,
        string $phoneticFamilyName,
        string $phoneticGivenName,
        GenderType $genderType,
        string $phoneNumber,
        string|null $email,
        string $password,
        DateTimeImmutable $joinedDate,
        CustomerStatus $status,
    ): Customer {
        return new self(
            $familyName,
            $givenName,
            $phoneticFamilyName,
            $phoneticGivenName,
            $genderType,
            $phoneNumber,
            $email,
            $password,
            $joinedDate,
            $status,
            $id,
        );
    }

    /** @return int<1, max>|null */
    public function getNewId(): int|null
    {
        return $this->newId;
    }

    /** @param int<1, max> $newId */
    public function setNewId(int $newId): void
    {
        $this->newId = $newId;
    }

    public function rejoin(
        string $familyName,
        string $givenName,
        string $phoneticFamilyName,
        string $phoneticGivenName,
        GenderType $genderType,
        string $phoneNumber,
        string|null $email,
        string $password,
        DateTimeImmutable $joinedDate,
    ): self {
        return new self(
            $familyName,
            $givenName,
            $phoneticFamilyName,
            $phoneticGivenName,
            $genderType,
            $phoneNumber,
            $email,
            $password,
            $joinedDate,
            CustomerStatus::TEMPORARY,
            $this->id,
        );
    }

    public function verified(): self
    {
        return new self(
            $this->familyName,
            $this->givenName,
            $this->phoneticFamilyName,
            $this->phoneticGivenName,
            $this->genderType,
            $this->phoneNumber,
            $this->email,
            $this->password,
            $this->joinedDate,
            CustomerStatus::VERIFIED,
            $this->id,
        );
    }

    public function resetPassword(string $password): self
    {
        return new self(
            $this->familyName,
            $this->givenName,
            $this->phoneticFamilyName,
            $this->phoneticGivenName,
            $this->genderType,
            $this->phoneNumber,
            $this->email,
            $password,
            $this->joinedDate,
            $this->status,
            $this->id,
        );
    }
}
