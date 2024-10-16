<?php

namespace App;

class StaticDatabase
{
    protected $_employees = [];
    protected $_accounts = [];
    protected $_users = [];

    public function __construct()
    {
        $this->_employees[1] = [
            'id' => 1,
            'name' => 'Karlos Huares',
            'role' => 'Ředitel',
            'image' => '/images/karlos_huares.jpg',
            'phone' => '+420 123 456 789',
            'email' => 'karlos@example.com',
            'description' => 'Karlos je odborný manažer, který se soustředí na optimalizaci výkonnosti týmu.'
        ];

        $this->_employees[2] = [
            'id' => 2,
            'name' => 'Richard Gere',
            'role' => 'Manažer',
            'image' => '/images/richard_gere.jpg',
            'phone' => '+420 987 654 321',
            'email' => 'richard@example.com',
            'description' => 'Richard je zodpovědný za správu projektů a týmů.'
        ];

        $this->_accounts[1] = [
            ['name' => 'E-mail', 'type' => 'Username/Password', 'expiration' => 'Permanentní'],
            ['name' => 'Administrátor', 'type' => 'Karta', 'expiration' => 'Dočasný']
        ];

        $this->_users = [
            ['name' => 'Karlos Huares', 'image' => '/images/karlos_huares.jpg'],
            ['name' => 'Richard Gere', 'image' => '/images/richard_gere.jpg']
        ];
    }

    public function getEmployees()
    {
        return $this->_employees;
    }

    public function getEmployee($id)
    {
        return $this->_employees[$id] ?? null;
    }

    public function getAccountsForEmployee($id)
    {
        return $this->_accounts[$id] ?? [];
    }

    public function getLatestUsers()
    {
        return $this->_users;
    }
}
