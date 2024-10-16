<?php

namespace App\Controllers;

class EmployeeAccountsController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function employeeAccounts($employeeId)
    {
        $accountsData = [
            1 => [
                ['name' => 'Gmail', 'type' => 'Email', 'expiration' => '2025-12-31'],
                ['name' => 'GitHub', 'type' => 'Developer', 'expiration' => '2026-01-15'],
            ],
            2 => [
                ['name' => 'Outlook', 'type' => 'Email', 'expiration' => '2024-11-10'],
            ],
        ];

        $employees = [
            1 => ['id' => 1, 'name' => 'Karlos Huares'],
            2 => ['id' => 2, 'name' => 'Richard Gere']
        ];

        $employee = $employees[$employeeId] ?? null;

        $accounts = $accountsData[$employeeId] ?? [];

        if ($employee) {
            echo $this->twig->render('employee_accounts.html.twig', [
                'employee' => $employee,
                'accounts' => $accounts
            ]);
        } else {
            echo $this->twig->render('error.html.twig', ['message' => 'Employee not found']);
        }
    }
}
