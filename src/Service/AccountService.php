<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Employee;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AccountService
{
    private EntityManagerInterface $entityManager;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private int $itemsPerPage;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager,int $itemsPerPage = 5)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function findEmployee(int $employeeId): ?Employee
    {
        return $this->entityManager->getRepository(Employee::class)->find($employeeId);
    }

    public function saveAccount(FormInterface $form, Request $request, Employee $employee): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->getData();
            $account->setEmployee($employee);

            $this->entityManager->persist($account);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    public function deleteAccount(Account $account, string $csrfToken): bool
    {
        if ($this->csrfTokenManager->isTokenValid(new CsrfToken('delete' . $account->getId(), $csrfToken))) {
            $this->entityManager->remove($account);
            $this->entityManager->flush();
            return true;
        }

        return false;
    }

    public function getPaginatedAccounts(int $page, ?string $searchQuery = null): array
    {
        $queryBuilder = $this->entityManager->getRepository(Account::class)->createQueryBuilder('a');

        if ($searchQuery) {
            $queryBuilder->where('a.name LIKE :search')
                ->setParameter('search', '%' . $searchQuery . '%');
        }
        $totalItems = (clone $queryBuilder)->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $accounts = $queryBuilder->setFirstResult(($page - 1) * $this->itemsPerPage)
            ->setMaxResults($this->itemsPerPage)
            ->getQuery()
            ->getResult();

        return [
            'accounts' => $accounts,
            'totalItems' => $totalItems,
            'itemsPerPage' => $this->itemsPerPage,
            'currentPage' => $page,
        ];
    }
}
