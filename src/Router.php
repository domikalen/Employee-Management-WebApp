<?php

namespace App;

use App\Controllers\EmployeesController;

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
                if ($id && $employee = $db->getEmployee($id)) {
                    echo $this->twig->render('employee_detail.html.twig', ['employee' => $employee]);
                } else {
                    echo $this->twig->render('error.html.twig', ['message' => 'Employee not found']);
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
