<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Role;
use App\Resource\RoleResource;
use Symfony\Component\Routing\RouterInterface;

class RoleFactory
{
    public function __construct(
        private RouterInterface $router,
    ) {
    }

    public function toResource(Role $role): RoleResource
    {
        return new RoleResource(
            _self: $this->router->generate('api_role_show', ['id' => $role->getId()]),
            title: $role->getTitle(),
            description: $role->getDescription(),
            isVisible: $role->getIsVisible()
        );
    }

    public function fromResource(RoleResource $resource, ?Role $role = null): Role
    {
        $role = $role ?? new Role();
        $role->setTitle($resource->title);
        $role->setDescription($resource->description);
        $role->setIsVisible($resource->isVisible);

        return $role;
    }
}
