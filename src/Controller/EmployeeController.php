<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PaginationService $paginationService;

    public function __construct(EntityManagerInterface $entityManager, PaginationService $paginationService)
    {
        $this->entityManager = $entityManager;
        $this->paginationService = $paginationService;
    }

    #[Route('/employees/{page}', name: 'employees', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function index(int $page = 1): Response
    {
        $totalItems = $this->entityManager->getRepository(Employee::class)->count([]);
        $pagination = $this->paginationService->getPagination($totalItems, $page);

        $offset = ($page - 1) * $this->paginationService->getItemsPerPage();
        $employees = $this->entityManager->getRepository(Employee::class)->findBy(
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

    #[Route('/employee/{id?}', name: 'employee_form', requirements: ['id' => '\d+'])]
    public function form(Request $request, Employee $employee = null): Response
    {
        $isNew = !$employee;
        if ($isNew) {
            $employee = new Employee();
        }

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            $imagePath = $this->handleImageUpload($imageFile, $this->getParameter('images_directory'), $employee->getImage());
            $employee->setImage($imagePath);

            if ($isNew) {
                $this->entityManager->persist($employee);
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('employees');
        }

        return $this->render('employee/form.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
            'isNew' => $isNew,
        ]);
    }

    private function handleImageUpload(?UploadedFile $imageFile, string $directory, ?string $currentImagePath): string
    {
        $defaultImage = "/images/default.jpg";

        if (!$imageFile) {
            return $currentImagePath ?? $defaultImage;
        }

        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = strtolower(str_replace(' ', '_', $originalFilename));
        $newFilename = $safeFilename . '.' . $imageFile->guessExtension();

        if (!file_exists($directory . '/' . $newFilename)) {
            try {
                $imageFile->move($directory, $newFilename);
            } catch (FileException $e) {
                throw new \Exception("Image upload failed: " . $e->getMessage());
            }
        }

        return "/images/" . $newFilename;
    }


    #[Route('/employee/{id}/delete', name: 'employee_delete', requirements: ['id' => '\d+'])]
    public function delete(Employee $employee): Response
    {
        $imagePath = $this->getParameter('images_directory') . $employee->getImage();

        if ($employee->getImage() && file_exists($imagePath) && $employee->getImage() !== '/images/default.jpg') {
            unlink($imagePath);
        }

        $this->entityManager->remove($employee);
        $this->entityManager->flush();

        return $this->redirectToRoute('employees');
    }
}
