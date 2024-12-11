<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Employee;
use App\Resource\EmployeeResource;
use Symfony\Component\Routing\RouterInterface;

class EmployeeFactory
{
    public function __construct(
        private RouterInterface $router,
    ) {
    }

    public function toResource(Employee $employee): EmployeeResource
    {
        return new EmployeeResource(
            _self: $this->router->generate('api_employee_show', ['id' => $employee->getId()]),
            name: $employee->getName(),
            email: $employee->getEmail(),
            phone: $employee->getPhone(),
            description: $employee->getDescription(),
            roles: array_map(
                fn($role) => $role->getTitle(),
                $employee->getRoles()->toArray()
            ),
            image: $employee->getImage(),
        );
    }

    public function fromResource(EmployeeResource $resource, Employee $employee): Employee
    {
        $employee = $employee ?? new Employee();
        $employee->setName($resource->name);
        $employee->setEmail($resource->email);
        $employee->setPhone($resource->phone);
        $employee->setDescription($resource->description);
        $employee->setImage($resource->image ?? 'new_user.jpg');
        if (!empty($resource->roles)) {
            foreach ($resource->roles as $role) {
                $employee->addRole($role);
            }
        }

        return $employee;
    }
}
