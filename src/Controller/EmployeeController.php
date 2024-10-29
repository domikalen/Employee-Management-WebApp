<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmployeeController extends AbstractController
{
    private EmployeeRepository $employeeRepository;
    private int $itemsPerPage = 5;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route(path: '/employees/{page}', name: 'employees', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function index(int $page = 1): Response
    {
        $offset = ($page - 1) * $this->itemsPerPage;

        $employees = $this->employeeRepository->findBy([], null, $this->itemsPerPage, $offset);

        $totalEmployees = $this->employeeRepository->count([]);
        $totalPages = ceil($totalEmployees / $this->itemsPerPage);

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
