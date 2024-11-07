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

    #[Route('/employee/{employee_id}/account/new', name: 'account_new', requirements: ['employee_id' => '\d+'])]
    public function new(Request $request, int $employee_id): Response
    {
        $employee = $this->entityManager->getRepository(Employee::class)->find($employee_id);

        if (!$employee) {
            throw $this->createNotFoundException('Employee not found.');
        }

        $account = new Account();
        $account->setEmployee($employee);

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($account);
            $this->entityManager->flush();

            return $this->redirectToRoute('employee_account', ['id' => $employee->getId()]);
        }

        return $this->render('account/form.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
            'title' => 'Create a New Account',
            'button_text' => 'Save Account'
        ]);
    }

    #[Route('/employee/{employee_id}/account/{id}/edit', name: 'account_edit')]
    public function edit(Request $request, int $employee_id, Account $account): Response
    {
        $employee = $this->entityManager->getRepository(Employee::class)->find($employee_id);

        if (!$employee || $account->getEmployee()->getId() !== $employee_id) {
            throw $this->createNotFoundException('Account or Employee not found.');
        }

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('employee_account', ['id' => $employee->getId()]);
        }

        return $this->render('account/form.html.twig', [
            'form' => $form->createView(),
            'account' => $account,
            'employee' => $employee,
            'title' => 'Edit Account',
            'button_text' => 'Update Account'
        ]);
    }

    #[Route('/account/{id}/delete', name: 'account_delete')]
    public function delete(Request $request, Account $account): Response
    {
        $form = $this->createForm(RemoveType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employeeId = $account->getEmployee()->getId();
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
