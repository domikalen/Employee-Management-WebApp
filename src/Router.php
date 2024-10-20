<?php

namespace App;

use App\Controllers\EmployeesController;
use App\Controllers\EmployeesDetailController;
use App\Controllers\EmployeeAccountsController;
use App\Controllers\MainController;
use App\Controllers\ErrorController;

class Router
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function route()
    {
        $page = $_GET['page'] ?? 'main';
        $id = $_GET['id'] ?? null;
        $db = new StaticDatabase();

        switch ($page) {
            case 'main':
                $contr = new MainController($this->twig, $db);
                $contr->index();
                break;
            case 'employees':
                $contr = new EmployeesController($this->twig, $db);
                $contr->employees();
                break;
            case 'employee_detail':
                $contr = new EmployeesDetailController($this->twig, $db);
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $contr->employeeDetail($id);
                break;
            case 'employee_accounts':
                $contr = new EmployeeAccountsController($this->twig, $db);
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $contr->employeeAccounts($id);
                break;
            default:
                $contr = new ErrorController($this->twig);
                $contr->showError("Page not found");
                break;
        }
    }
}
