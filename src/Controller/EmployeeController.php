<?php

namespace App\Controller;

use App\Database\StaticDatabase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractController
{
    private $db;

    public function __construct()
    {
        $this->db = new StaticDatabase();
    }

    #[Route(path: '/employees', name: 'employees')]
    public function index(): Response
    {
        // Fetch the employees only once
        $employees = $this->db->getEmployees();

        // Render the employee index template
        return $this->render('employee/index.html.twig', [
            'employees' => $employees
        ]);
    }
}
