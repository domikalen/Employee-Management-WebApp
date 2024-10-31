<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Account;

class AccountController extends AbstractController
{
    #[Route('/account/edit/{id}', name: 'edit_account')]
    public function editAccount(int $id, Request $request): Response
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);

        if (!$account) {
            throw $this->createNotFoundException('Account not found');
        }

        // Add your logic for editing the account here

        return $this->render('account/edit.html.twig', [
            'account' => $account,
        ]);
    }
}
