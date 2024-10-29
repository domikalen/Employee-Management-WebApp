<?php
namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository) {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route(path: '/', name: 'employee_index')]
    public function index(): Response
    {
        $users = $this->employeeRepository->findBy([], ['id' => 'DESC'], 10);

        return $this->render('home/index.html.twig', [
            'users' => $users
        ]);
    }
}
