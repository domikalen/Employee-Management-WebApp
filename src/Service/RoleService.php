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

    public function getPaginatedRoles(int $page, ?string $searchQuery = null): array
    {
        $queryBuilder = $this->entityManager->getRepository(Role::class)->createQueryBuilder('r');

        if ($searchQuery) {
            $queryBuilder->where('LOWER(r.title) LIKE :search')
                ->setParameter('search', '%' . strtolower($searchQuery) . '%');
        }

        $totalItems = (clone $queryBuilder)->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $roles = $queryBuilder->setFirstResult(($page - 1) * $this->itemsPerPage)
            ->setMaxResults($this->itemsPerPage)
            ->getQuery()
            ->getResult();

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
