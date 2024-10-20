<?php

namespace App\Controllers;

use App\StaticDatabase;

class EmployeeAccountsController
{
    private $twig;
    private $db;

    public function __construct($twig, StaticDatabase $db)
    {
        $this->twig = $twig;
        $this->db = $db;
    }

    public function employeeAccounts($employeeId)
    {
        $employee = $this->db->getEmployee($employeeId);
        $accounts = $this->db->getAccountsForEmployee($employeeId);

        if ($employee) {
            echo $this->twig->render('employee_accounts.html.twig', [
                'employee' => $employee,
                'accounts' => $accounts
            ]);
        } else {
            echo $this->twig->render('error.html.twig', [
                'message' => 'Employee not found'
            ]);
        }
    }
}

