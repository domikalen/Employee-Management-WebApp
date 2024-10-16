<?php

namespace App;

use App\Controllers\EmployeesController;
use App\Controllers\EmployeesDetailController;

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
            case 'main':
                echo $this->twig->render('index.html.twig', ['employees' => $db->getEmployees()]);
                break;
            case 'employees':
                $contr = new EmployeesController($this->twig);
                $contr->employees();
                break;
            case 'employee_detail':
                $contr = new EmployeesDetailController($this->twig);
                $eid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $contr->employeeDetail($eid);
                break;
            case 'error':
                echo $this->twig->render('error.html.twig');
                break;
            default:
                echo $this->twig->render('index.html.twig', ['users' => $db->getLatestUsers()]);
        }
    }
}
