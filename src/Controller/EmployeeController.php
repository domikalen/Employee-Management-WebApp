<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Service\EmployeeService;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    private EmployeeService $employeeService;
    private PaginationService $paginationService;

    public function __construct(EmployeeService $employeeService, PaginationService $paginationService)
    {
        $this->employeeService = $employeeService;
        $this->paginationService = $paginationService;
    }

    #[Route('/employees/{page}', name: 'employees', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function index(int $page = 1): Response
    {
        $paginationData = $this->employeeService->getPaginatedEmployees($page);
        $pagination = $this->paginationService->getPagination($paginationData['totalItems'], $page);

        return $this->render('employee/index.html.twig', [
            'employees' => $paginationData['employees'],
            'pagination' => $pagination,
        ]);
    }

    #[Route('/employee/create', name: 'employee_create')]
    #[Route('/employee/{id}/detail/edit', name: 'employee_detail_edit', requirements: ['id' => '\d+'])]
    public function form(Request $request, Employee $employee = null): Response
    {
        $isNew = !$employee;
        $employee = $employee ?? new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);

        if ($this->employeeService->processForm($form, $request, $employee, $this->getParameter('images_directory'))) {
            return $this->redirectToRoute('employees');
        }

        return $this->render('employee/form.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
            'isNew' => $isNew,
        ]);
    }


    #[Route('/employee/{id}/delete', name: 'employee_delete', requirements: ['id' => '\d+'])]
    public function delete(Employee $employee): Response
    {
        $this->employeeService->delete($employee, $this->getParameter('images_directory'));
        $this->addFlash('success', 'Employee deleted successfully.');
        return $this->redirectToRoute('employees');
    }

}
