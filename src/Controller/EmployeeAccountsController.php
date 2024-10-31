<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeAccountsController extends AbstractController
{
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    #[Route(path: '/employees_account/{id}', name: 'employee_account')]
    public function account(Employee $employee): Response
    {
        $accounts = $this->accountRepository->findBy(['employee' => $employee]);

        return $this->render('employees_account/index.html.twig', [
            'employee' => $employee,
            'accounts' => $accounts,
        ]);
    }
}
