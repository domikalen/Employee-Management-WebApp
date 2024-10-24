<?php

namespace App\Controller;

use App\Database\StaticDatabase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeAccountsController extends AbstractController {
    private $db;

    public function __construct()
    {
        $this->db = new StaticDatabase();
    }

    #[Route(path: '/employees_account/{id}', name: 'employee_account')]
    public function account(int $id): Response
    {
        $employee = $this->db->getEmployee($id);
        $accounts = $this->db->getAccountsForEmployee($id);

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

