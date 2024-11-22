<?php

namespace App\Resource;

class CollectionResource
{
    public int $count;
    public function __construct(
        public ?string $_self,
        public array $data,
    ) {
        $this->count = count($data);
    }

}
