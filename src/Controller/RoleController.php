<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Form\RemoveType;
use App\Service\RoleService;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SearchType;

class RoleController extends AbstractController
{
    private RoleService $roleService;
    private PaginationService $paginationService;

    public function __construct(RoleService $roleService, PaginationService $paginationService)
    {
        $this->roleService = $roleService;
        $this->paginationService = $paginationService;
    }

    #[Route('/roles/{page}', name: 'role_index', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function index(Request $request, int $page = 1): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $searchQuery = $form->get('query')->getData() ?: $request->query->get('query');

        if ($form->isSubmitted() && $form->isValid() && $page !== 1) {
            return $this->redirectToRoute('role_index', [
                'page' => 1,
                'query' => $searchQuery,
            ]);
        }

        $paginationData = $this->roleService->getPaginatedRoles($page, $searchQuery);
        if ($paginationData['totalItems'] == 0) {
            $pagination = [
                'currentPage' => 1,
                'totalPages' => 1,
                'hasPreviousPage' => false,
                'hasNextPage' => false,
            ];
        } else {
            $pagination = $this->paginationService->getPagination(
                $paginationData['totalItems'],
                $page,
                $searchQuery
            );
        }

        return $this->render('roles/index.html.twig', [
            'form' => $form->createView(),
            'roles' => $paginationData['roles'],
            'pagination' => $pagination,
            'query' => $searchQuery,
        ]);
    }


    #[Route('/role/new', name: 'role_new')]
    #[Route('/role/{id}/edit', name: 'role_edit')]
    public function form(Request $request, Role $role = null): Response
    {
        $form = $this->createForm(RoleType::class, $role ?? new Role());

        if ($this->roleService->processForm($form, $request, $role ?? new Role())) {
            return $this->redirectToRoute('role_index');
        }

        return $this->render('roles/form.html.twig', [
            'form' => $form->createView(),
            'title' => $role ? 'Edit Role' : 'Create Role',
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
            $this->roleService->deleteRole($role);
        }

        return $this->redirectToRoute('role_index');
    }
}
