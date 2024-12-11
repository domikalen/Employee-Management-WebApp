<?php

namespace App\Controller\Api;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Service\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/accounts', name: 'api_accounts_')]
class AccountController extends AbstractController
{
    private AccountService $accountService;
    private EntityManagerInterface $entityManager;
    private AccountRepository $accountRepository;

    public function __construct(
        AccountService $accountService,
        EntityManagerInterface $entityManager,
        AccountRepository $accountRepository
    ) {
        $this->accountService = $accountService;
        $this->entityManager = $entityManager;
        $this->accountRepository = $accountRepository;
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Account $account): Response
    {
        return $this->json([
            'id' => $account->getId(),
            'name' => $account->getName(),
            'type' => $account->getType(),
            'expiration' => $account->getExpiration()?->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $employeeId = $request->query->get('employeeId');

        if (!$employeeId) {
            return $this->json(['error' => 'Employee ID is required'], Response::HTTP_BAD_REQUEST);
        }

        $employee = $this->accountService->findEmployee($employeeId);
        if (!$employee) {
            return $this->json(['error' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        $account = $this->accountService->createAccount($data, $employee);

        return $this->json([
            'id' => $account->getId(),
            'name' => $account->getName(),
            'type' => $account->getType(),
            'expiration' => $account->getExpiration()?->format('Y-m-d H:i:s'),
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Account $account, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->accountService->updateAccount($account, $data);

        return $this->json([
            'id' => $account->getId(),
            'name' => $account->getName(),
            'type' => $account->getType(),
            'expiration' => $account->getExpiration()?->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Account $account): Response
    {
        $this->entityManager->remove($account);
        $this->entityManager->flush();

        return $this->json(['message' => 'Account deleted'], Response::HTTP_NO_CONTENT);
    }

}
