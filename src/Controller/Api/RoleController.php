<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Role;
use App\Factory\RoleFactory;
use App\Repository\RoleRepository;
use App\Resource\CollectionResource;
use App\Resource\RoleResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/api/roles', name: 'api_role_')]
class RoleController extends AbstractController
{
    public function __construct(
        private RoleRepository $roleRepository,
        private RoleFactory $roleFactory,
    ) {
    }

    #[Route(path: '', name: 'list', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $page = max((int) $request->query->get('page', 1), 1);
        $limit = max((int) $request->query->get('limit', 10), 1);
        $searchQuery = $request->query->get('search', null);

        $queryBuilder = $this->roleRepository->createQueryBuilder('r');

        if ($searchQuery) {
            $queryBuilder->where('LOWER(r.title) LIKE :search')
                ->setParameter('search', '%' . strtolower($searchQuery) . '%');
        }

        $totalItems = (clone $queryBuilder)->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $roles = $queryBuilder->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $this->json([
            '_self' => $this->generateUrl('api_role_list', [
                'page' => $page,
                'limit' => $limit,
                'search' => $searchQuery
            ]),
            'total' => $totalItems,
            'page' => $page,
            'limit' => $limit,
            'data' => array_map(
                fn (Role $role) => $this->roleFactory->toResource($role),
                $roles
            ),
        ]);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Role $role): Response
    {
        return $this->json($this->roleFactory->toResource($role));
    }

    #[Route(path: '', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $manager
    ): Response {
        $resource = $serializer->deserialize(
            $request->getContent(),
            RoleResource::class,
            'json'
        );

        $role = $this->roleFactory->fromResource($resource);

        $errors = $validator->validate($role);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorsArray], 400);
        }

        $manager->persist($role);
        $manager->flush();

        return $this->json($this->roleFactory->toResource($role), Response::HTTP_CREATED);
    }

    #[Route(path: '/{id}', name: 'update', methods: ['PUT'])]
    public function update(
        Role $role,
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $manager
    ): Response {
        $resource = $serializer->deserialize(
            $request->getContent(),
            RoleResource::class,
            'json'
        );

        $role = $this->roleFactory->fromResource($resource, $role);

        $errors = $validator->validate($role);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorsArray], 400);
        }

        $manager->flush();

        return $this->json($this->roleFactory->toResource($role));
    }

    #[Route(path: '/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Role $role, EntityManagerInterface $manager): Response
    {
        $manager->remove($role);
        $manager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
