<?php
declare(strict_types=1);

namespace App\Resource;

class EmployeeResource
{
    public function __construct(
        public ?string $_self,
        public string $name,
        public string $email,
        public string $phone,
        public ?string $description = null,
        public ?array $roles = null,
        public ?string $image = null
    ) {

    }
}
