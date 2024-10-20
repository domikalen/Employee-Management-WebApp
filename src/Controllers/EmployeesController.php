<?php

namespace App\Controllers;

use App\StaticDatabase;

class EmployeesController {
    private $twig;
    private $db;

    public function __construct($twig, StaticDatabase $db) {
        $this->twig = $twig;
        $this->db = $db;
    }

    public function employees() {
        echo $this->twig->render('employees.html.twig', [
            'employees' => $this->db->getEmployees()
        ]);
    }
}
