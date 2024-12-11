<?php
declare(strict_types=1);

namespace App\Resource;

class RoleResource
{
    public function __construct(
        public ?string $_self,
        public string $title,
        public ?string $description,
        public bool $isVisible
    ) {
    }
}
