<?php

namespace App\Controller;

use App\Database\StaticDatabase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class ErrorController extends AbstractController
{
    public function __construct()
    {

    }
    #[Route(path: '/error', name: 'show_error')]

    public function showError(): Response
    {
        return $this->render('error/index.html.twig', [

        ]);
    }
}
