<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Employee;
use App\Form\AccountType;
use App\Form\RemoveType;
use App\Service\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    #[Route('/employee/{employee_id}/account/new', name: 'account_new', requirements: ['employee_id' => '\d+'])]
    #[Route('/employee/{employee_id}/account/{id}/edit', name: 'account_edit', requirements: ['employee_id' => '\d+', 'id' => '\d+'])]
    public function form(Request $request, int $employee_id, Account $account = null): Response
    {
        $employee = $this->accountService->findEmployee($employee_id);

        if (!$employee) {
            throw $this->createNotFoundException('Employee not found.');
        }

        $isNew = !$account;
        if ($isNew) {
            $account = new Account();
        }

        $form = $this->createForm(AccountType::class, $account);

        if ($this->accountService->saveAccount($form, $request, $employee)) {
            return $this->redirectToRoute('employee_account', ['id' => $employee->getId()]);
        }

        return $this->render('account/form.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
            'title' => $isNew ? 'Create a New Account' : 'Edit Account',
            'button_text' => $isNew ? 'Save Account' : 'Update Account',
        ]);
    }

    #[Route('/account/{id}/delete', name: 'account_delete', methods: ['POST'])]
    public function delete(Request $request, Account $account): Response
    {
        if ($this->accountService->deleteAccount($account, $request->request->get('_token'))) {
            return $this->redirectToRoute('employee_account', ['id' => $account->getEmployee()->getId()]);
        }

        $this->addFlash('error', 'Invalid CSRF token.');
        return $this->redirectToRoute('employee_account', ['id' => $account->getEmployee()->getId()]);
    }
}
