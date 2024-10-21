<?php
namespace App\Controller;

use App\Database\StaticDatabase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController{
    private $db;

    public function __construct() {
        $this->db = new StaticDatabase();
    }
    #[Route(path: '/home', name: 'employee_index')]
    public function index() : Response{
        return $this->render('home/index.html.twig', [
            'users' => $this->db->getLatestUsers()
        ]);
    }
}
