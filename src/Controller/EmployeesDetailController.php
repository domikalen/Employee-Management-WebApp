<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeesDetailController extends AbstractController
{
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route(path: '/employee/{id}/detail', name: 'employee_detail')]
    public function detail(int $id): Response
    {
        $employee = $this->employeeRepository->find($id);

        if ($employee) {
            return $this->render('employee_detail/index.html.twig', [
                'employee' => $employee
            ]);
        } else {
            return $this->render('error/error.html.twig', [
                'message' => 'Employee not found'
            ]);
        }
    }
}
