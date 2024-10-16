<?php

namespace App;

use App\StaticDatabase;

class Router
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function route()
    {
        $page = $_GET['page'] ?? 'index';
        $id = $_GET['id'] ?? null;

        $db = new StaticDatabase();

        switch ($page) {
            case 'employees':
                echo $this->twig->render('employees.html.twig', ['employees' => $db->getEmployees()]);
                break;
            case 'employee_detail':
                if ($id && $employee = $db->getEmployee($id)) {
                    echo $this->twig->render('employee_detail.html.twig', ['employee' => $employee]);
                } else {
                    echo $this->twig->render('error.html.twig', ['message' => 'Employee not found']);
                }
                break;
            case 'employee_accounts':
                if ($id && $accounts = $db->getAccountsForEmployee($id)) {
                    $employee = $db->getEmployee($id);
                    echo $this->twig->render('employee_accounts.html.twig', [
                        'accounts' => $accounts,
                        'employee' => $employee
                    ]);
                } else {
                    echo $this->twig->render('error.html.twig', ['message' => 'Accounts not found']);
                }
                break;
            case 'error':
                echo $this->twig->render('error.html.twig');
                break;
            default:
                echo $this->twig->render('index.html.twig', ['users' => $db->getLatestUsers()]);
        }
    }
}
