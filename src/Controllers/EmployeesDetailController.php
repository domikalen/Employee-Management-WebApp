<?php

namespace App\Controllers;


class EmployeesDetailController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function employeeDetail($employeeId)
    {
        $employees = [
            1 => ['id' => 1, 'name' => 'Karlos Huares', 'role' => 'Ředitel', 'phone' => '+420 123 456 789', 'email' => 'karlos@example.com', 'description' => 'Karlos je odborný manažer, který se soustředí na optimalizaci výkonnosti týmu.'],
            2 => ['id' => 2, 'name' => 'Richard Gere', 'role' => 'Manažer', 'phone' => '+420 987 654 321', 'email' => 'richard@example.com', 'description' => 'Richard je zodpovědný за správу проектů и týmов.']
        ];
        $employee = $employees[$employeeId] ?? null;
        if ($employee) {
            echo $this->twig->render('employee_detail.html.twig', ['employee' => $employee]);
        } else {
            echo $this->twig->render('error.html.twig', ['message' => 'Employee not found']);
        }
    }
}