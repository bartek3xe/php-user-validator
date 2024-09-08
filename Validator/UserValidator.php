<?php

declare(strict_types=1);

namespace Validator;

class UserValidator
{
    public function validateEmail(string $email): bool
    {
        return match (true) {
            !str_contains($email, '@') => false,
            !$this->isValidEmailParts($email) => false,
            !$this->hasValidDomainParts($this->getDomainParts($email)) => false,
            !$this->isTldValid($this->getTld($email)) => false,
            default => true,
        };
    }

    public function validatePassword(string $password): bool
    {
        return match (true) {
            strlen($password) < 8 => false,
            !preg_match('/[A-Z]/', $password) => false,
            !preg_match('/[a-z]/', $password) => false,
            !preg_match('/[0-9]/', $password) => false,
            !preg_match('/[\W_]/', $password) => false,
            default => true,
        };
    }

    private function isValidEmailParts(string $email): bool
    {
        $parts = explode('@', $email);
        return count($parts) === 2 && !empty($parts[0]) && str_contains($parts[1], '.');
    }

    private function hasValidDomainParts(array $domainParts): bool
    {
        $validParts = array_filter($domainParts, fn($part) => strlen($part) >= 2 && ctype_alpha($part));

        return count($validParts) === count($domainParts);
    }

    private function isTldValid(string $tld): bool
    {
        return strlen($tld) >= 2 && ctype_alpha($tld);
    }

    private function getDomainParts(string $email): array
    {
        return explode('.', explode('@', $email)[1]);
    }

    private function getTld(string $email): string
    {
        $domainParts = $this->getDomainParts($email);
        return end($domainParts);
    }
}
