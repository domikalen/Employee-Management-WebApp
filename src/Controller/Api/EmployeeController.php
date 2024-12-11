<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Entity\Role;
use App\Factory\EmployeeFactory;
use App\Repository\EmployeeRepository;
use App\Repository\RoleRepository;
use App\Resource\EmployeeResource;
use App\Service\EmployeeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/api/employee', name: 'api_employee_')]
class EmployeeController extends AbstractController
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private EmployeeFactory $employeeFactory,
        private EmployeeService $employeeService,
        private EntityManagerInterface $manager,
        private string $imageDirectory
    ) {}

    #[Route(path: '', name: 'list', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $page = max((int) $request->query->get('page', 1), 1);
        $searchQuery = $request->query->get('search', null);

        $paginationData = $this->employeeService->getPaginatedEmployees($page, $searchQuery);

        return $this->json([
            '_self' => $this->generateUrl('api_employee_list', ['page' => $page, 'search' => $searchQuery]),
            'total' => $paginationData['totalItems'],
            'page' => $paginationData['currentPage'],
            'limit' => $paginationData['itemsPerPage'],
            'data' => array_map(
                fn(Employee $employee) => $this->employeeFactory->toResource($employee),
                $paginationData['employees']
            ),
        ]);
    }


    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->json($this->employeeFactory->toResource($employee));
    }

    #[Route(path: '/{id}/add-role/{roleId}', name: 'add_role', methods: ['POST'])]
    public function addRole(Employee $employee, int $roleId, RoleRepository $roleRepository): Response
    {

        $role = $roleRepository->find($roleId);
        if (!$role) {
            return $this->json(['error' => 'Role not found'], Response::HTTP_BAD_REQUEST);
        }


        $employee->addRole($role);

        $this->manager->persist($employee);
        $this->manager->flush();

        return $this->json(['message' => 'Role added successfully']);
    }

    #[Route(path: '/{id}/remove-role/{roleId}', name: 'remove_role', methods: ['POST'])]
    public function removeRole(Employee $employee, int $roleId, RoleRepository $roleRepository): Response
    {

        $role = $roleRepository->find($roleId);
        if (!$role) {
            return $this->json(['error' => 'Role not found'], Response::HTTP_BAD_REQUEST);
        }

        $employee->removeRole($role);


        $this->manager->persist($employee);
        $this->manager->flush();

        return $this->json(['message' => 'Role removed successfully']);
    }

    #[Route(path: '', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        RoleRepository $roleRepository
    ): Response {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $resource = new EmployeeResource(
            _self: null,
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'],
            description: $data['description'] ?? null,
            roles: [],
            image: $data['image'] ?? null
        );

        $employee = $this->employeeFactory->fromResource($resource, new Employee());

        $roleIds = $data['roles'] ?? [];
        $roles = [];
        foreach ($roleIds as $roleId) {
            $role = $roleRepository->find($roleId);
            if (!$role) {
                return $this->json(['error' => sprintf('Role with ID %d not found.', $roleId)], Response::HTTP_BAD_REQUEST);
            }
            $roles[] = $role;
            $employee->addRole($role);
        }

        $errors = $validator->validate($employee);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->manager->persist($employee);
        $this->manager->flush();

        return $this->json($this->employeeFactory->toResource($employee), Response::HTTP_CREATED);
    }


    #[Route(path: '/{id}', name: 'update', methods: ['PUT'])]
    public function update(
        Employee $employee,
        Request $request,
        ValidatorInterface $validator,
        RoleRepository $roleRepository
    ): Response {
        $data = json_decode($request->getContent(), true);

        $roleIds = $data['roles'] ?? [];
        $roles = [];
        foreach ($roleIds as $roleId) {
            $role = $roleRepository->find($roleId);
            if (!$role) {
                return $this->json(['error' => sprintf('Role with ID %d not found.', $roleId)], Response::HTTP_BAD_REQUEST);
            }
            $roles[] = $role;
        }

        $employee->setName($data['name']);
        $employee->setEmail($data['email']);
        $employee->setPhone($data['phone']);
        $employee->setDescription($data['description'] ?? null);

        $employee->clearRoles();
        foreach ($roles as $role) {
            $employee->addRole($role);
        }

        $errors = $validator->validate($employee);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->manager->flush();

        return $this->json($this->employeeFactory->toResource($employee));
    }

    #[Route(path: '/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Employee $employee): Response
    {
        $this->employeeService->delete($employee, $this->imageDirectory);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
