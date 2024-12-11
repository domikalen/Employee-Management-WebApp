<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Account;
use App\Entity\Employee;
use App\Factory\AccountFactory;
use App\Repository\AccountRepository;
use App\Service\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/{employeeId}/accounts', name: 'api_accounts_')]
class AccountController extends AbstractController
{
    public function __construct(
        private AccountRepository $accountRepository,
        private AccountFactory $accountFactory,
        private AccountService $accountService,
        private EntityManagerInterface $entityManager,
    ) {}

    #[Route(path: '', name: 'list_by_employee', methods: ['GET'])]
    public function listByEmployee(int $employeeId): Response
    {
        $employee = $this->accountService->findEmployee($employeeId);

        if (!$employee) {
            return $this->json(['error' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        $accounts = $this->accountRepository->findBy(['employee' => $employee]);

        return $this->json([
            '_self' => $this->generateUrl('api_accounts_list_by_employee', ['employeeId' => $employeeId]),
            'data' => array_map(
                fn(Account $account) => $this->accountFactory->toResource($account),
                $accounts
            ),
        ]);
    }

    #[Route(path: '', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $employeeId = $data['employeeId'] ?? null;

        if (!$employeeId) {
            return $this->json(['error' => 'Employee ID is required'], Response::HTTP_BAD_REQUEST);
        }

        $employee = $this->accountService->findEmployee($employeeId);
        if (!$employee) {
            return $this->json(['error' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        $account = $this->accountFactory->fromArray($data, new Account());
        $account->setEmployee($employee);

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $this->json($this->accountFactory->toResource($account), Response::HTTP_CREATED);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Account $account): Response
    {
        return $this->json($this->accountFactory->toResource($account));
    }

    #[Route(path: '/{id}', name: 'update', methods: ['PUT'])]
    public function update(Account $account, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $account = $this->accountFactory->fromArray($data, $account);
        $this->entityManager->flush();

        return $this->json($this->accountFactory->toResource($account));
    }

    #[Route(path: '/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Account $account): Response
    {
        $this->entityManager->remove($account);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
