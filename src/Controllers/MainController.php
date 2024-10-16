<?php
namespace App\Controllers;

class MainController {
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function index() {
        echo $this->twig->render('index.html.twig');
    }
}
