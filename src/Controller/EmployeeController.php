<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractController
{
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route(path: '/employees', name: 'employees')]
    public function index(): Response
    {
        $employees = $this->employeeRepository->findAll();

        return $this->render('employee/index.html.twig', [
            'employees' => $employees
        ]);
    }
}
