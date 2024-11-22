<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Account;
use App\Entity\Employee;
use App\Resource\AccountResource;
use Symfony\Component\Routing\RouterInterface;

class AccountFactory
{
    public function fromArray(array $data, Account $account): Account
    {
        if (isset($data['name'])) {
            $account->setName($data['name']);
        }
        if (isset($data['type'])) {
            $account->setType($data['type']);
        }
        if (isset($data['expiration'])) {
            $account->setExpiration(new \DateTime($data['expiration']));
        }
        if (isset($data['employee']) && $data['employee'] instanceof Employee) {
            $account->setEmployee($data['employee']);
        }

        return $account;
    }

    public function toResource(Account $account): array
    {
        return [
            'id' => $account->getId(),
            'name' => $account->getName(),
            'type' => $account->getType(),
            'expiration' => $account->getExpiration()->format('Y-m-d\TH:i:s'),
            'employee' => $account->getEmployee()->getId(),
        ];
    }
}
