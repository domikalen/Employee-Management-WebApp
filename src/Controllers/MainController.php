<?php
namespace App\Controllers;

use App\StaticDatabase;

class MainController {
    private $twig;
    private $db;

    public function __construct($twig, StaticDatabase $db) {
        $this->twig = $twig;
        $this->db = $db;
    }

    public function index() {
        echo $this->twig->render('index.html.twig', [
            'users' => $this->db->getLatestUsers()
        ]);
    }
}
