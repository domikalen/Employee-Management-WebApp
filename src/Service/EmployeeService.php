<?php

namespace App\Service;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EmployeeService
{
    private EntityManagerInterface $entityManager;
    private int $itemsPerPage;

    public function __construct(EntityManagerInterface $entityManager, int $itemsPerPage = 5)
    {
        $this->entityManager = $entityManager;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getPaginatedEmployees(int $page): array
    {
        $totalItems = $this->entityManager->getRepository(Employee::class)->count([]);
        $offset = ($page - 1) * $this->itemsPerPage;

        $employees = $this->entityManager->getRepository(Employee::class)->findBy(
            [],
            null,
            $this->itemsPerPage,
            $offset
        );

        return [
            'employees' => $employees,
            'totalItems' => $totalItems,
            'itemsPerPage' => $this->itemsPerPage,
            'currentPage' => $page,
        ];
    }

    public function processForm(FormInterface $form, Request $request, Employee $employee, string $directory): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imagePath = $this->handleImageUpload($imageFile, $directory, $employee->getImage());
                $employee->setImage($imagePath);
            }

            $this->entityManager->persist($employee);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    public function delete(Employee $employee, string $directory): void
    {
        $imagePath = $directory . $employee->getImage();
        if ($employee->getImage() && file_exists($imagePath) && $employee->getImage() !== '/images/new_user.jpg') {
            unlink($imagePath);
        }

        $this->entityManager->remove($employee);
        $this->entityManager->flush();
    }

    private function handleImageUpload(?UploadedFile $imageFile, string $directory, ?string $currentImagePath): string
    {
        $defaultImage = "/images/new_user.jpg";

        if (!$imageFile) {
            return $currentImagePath ?? $defaultImage;
        }

        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = strtolower(str_replace(' ', '_', $originalFilename));
        $newFilename = $safeFilename . '.' . $imageFile->guessExtension();

        if (!file_exists($directory . '/' . $newFilename)) {
            try {
                $imageFile->move($directory, $newFilename);
            } catch (\Exception $e) {
                throw new \Exception("Image upload failed: " . $e->getMessage());
            }
        }

        return "/images/" . $newFilename;
    }
}
