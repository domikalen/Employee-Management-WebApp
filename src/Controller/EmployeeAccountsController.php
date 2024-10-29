<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeAccountsController extends AbstractController
{
    private $employeeRepository;
    private $accountRepository;

    public function __construct(EmployeeRepository $employeeRepository, AccountRepository $accountRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->accountRepository = $accountRepository;
    }

    #[Route(path: '/employees_account/{id}', name: 'employee_account')]
    public function account(int $id): Response
    {
        $employee = $this->employeeRepository->find($id);
        $accounts = $this->accountRepository->findBy(['employee' => $id]);

        if ($employee) {
            return $this->render('employees_account/index.html.twig', [
                'employee' => $employee,
                'accounts' => $accounts
            ]);
        } else {
            return $this->render('error/error.html.twig', [
                'message' => 'Employee not found'
            ]);
        }
    }
}
