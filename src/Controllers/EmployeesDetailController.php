<?php

namespace App\Controllers;

use App\StaticDatabase;

class EmployeesDetailController
{
    private $twig;
    private $db;

    public function __construct($twig, StaticDatabase $db)
    {
        $this->twig = $twig;
        $this->db = $db;
    }


    public function employeeDetail($employeeId)
    {
        $employee = $this->db->getEmployee($employeeId);
        if ($employee) {
            echo $this->twig->render('employee_detail.html.twig', [
                'employee' => $employee
            ]);
        } else {
            echo $this->twig->render('error.html.twig', [
                'message' => 'Employee not found'
            ]);
        }
    }

}
