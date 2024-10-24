<?php

namespace App\Database;

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

        $this->_employees[3] = [
            'id' => 3,
            'name' => 'Tokaev Tigr',
            'role' => 'Diktátor',
            'image' => '/images/tokaev_tigr.jpg',
            'phone' => '+420 999 999 999',
            'email' => 'president@example.com',
            'description' => 'Nejhorší diktátor v historii Kazachstánu.'
        ];

        $this->_employees[4] = [
            'id' => 4,
            'name' => 'Phil Hellmuth',
            'role' => 'CEO',
            'image' => '/images/phil_hellmuth.jpg',
            'phone' => '+420 777 777 777',
            'email' => 'gambler@example.com',
            'description' => 'Prohrává všechny peníze firmy v kasinu'
        ];

        $this->_employees[5] = [
            'id' => 5,
            'name' => 'Eric Cartman',
            'role' => 'terrorist',
            'image' => '/images/eric_cartman.jpg',
            'phone' => '+420 666 666 666',
            'email' => 'benladen@example.com',
            'description' => 'Tajný agent firmy v South Parku'
        ];

        $this->_employees[6] = [
            'id' => 6,
            'name' => 'Nacho Varga',
            'role' => 'Distributor',
            'image' => '/images/nacho_varga.jpg',
            'phone' => '+420 123 456 789',
            'email' => 'bettercallsoul@example.com',
            'description' => 'Tajný agent firmy v Mexico'
        ];

        $this->_accounts[1] = [
            ['name' => 'Primary Email', 'type' => 'Email/Password + 2FA', 'expiration' => 'Permanent'],
            ['name' => 'Admin Access', 'type' => 'Smartcard with Biometrics', 'expiration' => 'Temporary (3 months)']
        ];

        $this->_accounts[2] = [
            ['name' => 'Corporate Account', 'type' => 'SSO (Single Sign-On)', 'expiration' => 'Permanent'],
            ['name' => 'Admin Access', 'type' => 'Keycard + PIN', 'expiration' => 'Temporary (6 months)']
        ];

        $this->_accounts[3] = [
            ['name' => 'Business Email', 'type' => 'Username/Password + MFA', 'expiration' => 'Permanent'],
            ['name' => 'Super Admin', 'type' => 'Biometric Card', 'expiration' => 'Temporary (7 year)'],
            ['name' => 'KNB Admin', 'type' => 'gun', 'expiration' => 'Temporary (7 year)'],
            ['name' => 'The office of the President', 'type' => 'extortion', 'expiration' => 'Temporary (7 year)'],
            ['name' => 'Bandit', 'type' => ' banditry', 'expiration' => 'Temporary (7 year)']
        ];

        $this->_accounts[4] = [
            ['name' => 'Company Portal Login', 'type' => 'Passwordless (Email Link)', 'expiration' => 'Token-based (24 hours)'],
            ['name' => 'Admin Access', 'type' => 'Encrypted Key + Password', 'expiration' => 'Temporary (6 months)'],
        ];

        $this->_accounts[5] = [
            ['name' => 'Social Media Login', 'type' => 'OAuth (Google/Facebook)', 'expiration' => 'Token-based (1 week)'],
            ['name' => 'Guest Admin Access', 'type' => 'Time-limited PIN', 'expiration' => 'Temporary (1 week)']
        ];

        $this->_accounts[6] = [
            ['name' => 'Encrypted Email', 'type' => 'Public Key/Private Key Pair', 'expiration' => 'Permanent'],
            ['name' => 'Root Access', 'type' => 'Smartcard + Password', 'expiration' => 'Temporary (1 year)']
        ];


        $this->_users = [
            ['name' => 'Karlos Huares', 'image' => '/images/karlos_huares.jpg'],
            ['name' => 'Richard Gere', 'image' => '/images/richard_gere.jpg'],
            ['name' => 'Tokaev Tigr', 'image' => '/images/tokaev_tigr.jpg'],
            ['name' => 'Phil Hellmuth', 'image' => '/images/phil_hellmuth.jpg'],
            ['name' => 'Eric Cartman', 'image' => '/images/eric_cartman.jpg'],
            ['name' => 'Nacho Varga', 'image' => '/images/nacho_varga.jpg']
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
