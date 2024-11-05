<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Employee;
use App\Form\AccountType;
use App\Form\RemoveType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/new/{employee_id}', name: 'account_new', requirements: ['employee_id' => '\d+'])]
    public function new(Request $request, int $employee_id): Response
    {
        // Fetch the employee by ID
        $employee = $this->entityManager->getRepository(Employee::class)->find($employee_id);

        if (!$employee) {
            throw $this->createNotFoundException('Employee not found.');
        }

        $account = new Account();
        $account->setEmployee($employee); // Associate the account with the employee

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($account);
            $this->entityManager->flush();

            return $this->redirectToRoute('employee_account', ['id' => $employee->getId()]);
        }

        return $this->render('account/new.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
        ]);
    }

    #[Route('/account/{id}/edit', name: 'account_edit')]
    public function edit(Request $request, Account $account): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('employee_account', ['id' => $account->getEmployee()->getId()]);
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
            'account' => $account,
        ]);
    }

    #[Route('/account/{id}/delete', name: 'account_delete')]
    public function delete(Request $request, Account $account): Response
    {
        $form = $this->createForm(RemoveType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employeeId = $account->getEmployee()->getId(); // Get employee ID before deletion
            $this->entityManager->remove($account);
            $this->entityManager->flush();

            return $this->redirectToRoute('employee_account', ['id' => $employeeId]);
        }

        return $this->render('account/delete.html.twig', [
            'form' => $form->createView(),
            'account' => $account,
        ]);
    }
}
