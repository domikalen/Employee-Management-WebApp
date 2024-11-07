<?php
namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Form\RemoveType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoleController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/roles', name: 'role_index')]
    public function index(): Response
    {
        $roles = $this->entityManager->getRepository(Role::class)->findAll();

        return $this->render('roles/index.html.twig', [
            'roles' => $roles,
        ]);
    }

    #[Route('/role/new', name: 'role_new')]
    #[Route('/role/{id}/edit', name: 'role_edit')]
    public function form(Request $request, Role $role = null): Response
    {
        $isNew = !$role;
        if ($isNew) {
            $role = new Role();
        }

        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($role);
            $this->entityManager->flush();

            return $this->redirectToRoute('role_index');
        }

        return $this->render('roles/form.html.twig', [
            'form' => $form->createView(),
            'title' => $isNew ? 'Create Role' : 'Edit Role',
        ]);
    }

    #[Route('/roles/{id}/confirm-delete', name: 'role_confirm_delete', methods: ['GET', 'POST'])]
    public function confirmDelete(Request $request, Role $role): Response
    {
        $form = $this->createForm(RemoveType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->delete($request, $role);
        }

        return $this->render('roles/delete.html.twig', [
            'form' => $form->createView(),
            'role' => $role,
        ]);
    }

    #[Route('/roles/{id}/delete', name: 'role_delete', methods: ['POST'])]
    public function delete(Request $request, Role $role): Response
    {
        if ($this->isCsrfTokenValid('delete' . $role->getId(), $request->request->get('_token'))) {
            foreach ($role->getEmployees() as $employee) {
                $employee->removeRole($role);
            }

            $this->entityManager->remove($role);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('role_index');
    }
}
