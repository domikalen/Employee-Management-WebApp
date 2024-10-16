<?php

namespace App\Controllers;


class EmployeesController {
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function employees(){
        $employees = [
            1 => ['id' => 1, 'name' => 'Karlos Huares', 'role' => 'Ředitel', 'phone' => '+420 123 456 789', 'email' => 'karlos@example.com', 'description' => 'Karlos je odborný manažer, který se soustředí na optimalizaci výkonnosti týmu.'],
            2 => ['id' => 2, 'name' => 'Richard Gere', 'role' => 'Manažer', 'phone' => '+420 987 654 321', 'email' => 'richard@example.com', 'description' => 'Richard je zodpovědný за správу проектů и týmов.']
        ];
        echo $this->twig->render('employees.html.twig', ['employees' => $employees]);
    }

}
