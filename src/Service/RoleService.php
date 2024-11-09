<?php

namespace App\Service;

use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RoleService
{
    private EntityManagerInterface $entityManager;
    private int $itemsPerPage;

    public function __construct(EntityManagerInterface $entityManager, int $itemsPerPage = 5)
    {
        $this->entityManager = $entityManager;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getPaginatedRoles(int $page): array
    {
        $totalItems = $this->entityManager->getRepository(Role::class)->count([]);
        $offset = ($page - 1) * $this->itemsPerPage;

        $roles = $this->entityManager->getRepository(Role::class)->findBy(
            [],
            null,
            $this->itemsPerPage,
            $offset
        );

        return [
            'roles' => $roles,
            'totalItems' => $totalItems,
            'itemsPerPage' => $this->itemsPerPage,
            'currentPage' => $page,
        ];
    }

    public function processForm(FormInterface $form, Request $request, Role $role): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->getData();
            $this->entityManager->persist($role);
            $this->entityManager->flush();
            return true;
        }

        return false;
    }



    public function deleteRole(Role $role): void
    {
        foreach ($role->getEmployees() as $employee) {
            $employee->removeRole($role);
        }

        $this->entityManager->remove($role);
        $this->entityManager->flush();
    }
}
