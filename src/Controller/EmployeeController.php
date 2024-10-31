<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    private EmployeeRepository $employeeRepository;
    private PaginationService $paginationService;

    public function __construct(EmployeeRepository $employeeRepository, PaginationService $paginationService)
    {
        $this->employeeRepository = $employeeRepository;
        $this->paginationService = $paginationService;
    }


    #[Route('/employees/{page}', name: 'employees', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function index(int $page = 1): Response
    {
        $totalItems = $this->employeeRepository->count([]);
        $pagination = $this->paginationService->getPagination($totalItems, $page);

        $offset = ($page - 1) * $this->paginationService->getItemsPerPage();
        $employees = $this->employeeRepository->findBy(
            [],
            null,
            $this->paginationService->getItemsPerPage(),
            $offset
        );

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
            'pagination' => $pagination,
        ]);
    }

    public function show(int $id): Response
    {
        $employee = $this->employeeRepository->find($id);

        if (!$employee) {
            throw $this->createNotFoundException('Employee not found');
        }

        return $this->render('employee_detail/index.html.twig', [
            'employee' => $employee,
        ]);
    }
}
