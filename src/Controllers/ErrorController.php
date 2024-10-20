<?php

namespace App\Controllers;

class ErrorController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function showError($message = "An error occurred")
    {
        echo $this->twig->render('error.html.twig', [
            'message' => $message
        ]);
    }
}
