<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Auth;

interface CustomerAuthenticatorInterface
{
    public function login(string $username, string $password): void;

    public function logout(): void;

    public function isValid(): bool;

    public function isExpired(): bool;

    public function getUserName(): string|null;

    /** @return array<string, scalar> */
    public function getUserData(): array;

    public function getAuthRedirect(): string;

    public function getUnauthRedirect(): string;

    public function getIdentity(): CustomerIdentity;
}
