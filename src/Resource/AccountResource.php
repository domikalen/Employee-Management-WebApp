<?php

declare(strict_types=1);

namespace App\Resource;

class AccountResource
{
    public function __construct(
        public ?string $_self,
        public ?int $id,
        public ?string $name,
        public ?string $type,
        public ?string $expiration,
        public ?int $employeeId,
    ) {}
}
