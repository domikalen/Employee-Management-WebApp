<?php

namespace App\Controller;

use App\Database\StaticDatabase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeesDetailController extends AbstractController
{
    private $db;

    public function __construct()
    {
        $this->db = new StaticDatabase();
    }

    #[Route(path: '/employees_detail/{id}', name: 'details')]
    public function detail(int $id): Response
    {
        $employee = $this->db->getEmployee($id);
        if ($employee) {
            return $this->render('employee_detail/index.html.twig', [
                'employee' => $employee
            ]);
        } else {
            return $this->render('error/index.html.twig', [
                'message' => 'Employee not found'
            ]);
        }
    }

}
