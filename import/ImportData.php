<?php

use App\Kernel;
use App\Entity\Employee;
use App\Entity\Account;
use App\Entity\Role;
use Symfony\Component\Dotenv\Dotenv;
use Doctrine\ORM\EntityManagerInterface;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

class ImportData
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function import()
    {
        $employeesData = [
            [
                'name' => 'Karlos Huares',
                'role' => 'Ředitel',
                'image' => '/images/karlos_huares.jpg',
                'phone' => '+420 123 456 780',
                'email' => 'karlos@example.com',
                'description' => 'Karlos je odborný manažer, který se soustředí na optimalizaci výkonnosti týmu.',
            ],
            [
                'name' => 'Richard Gere',
                'role' => 'Manažer',
                'image' => '/images/richard_gere.jpg',
                'phone' => '+420 987 654 321',
                'email' => 'richard@example.com',
                'description' => 'Richard je zodpovědný za správu projektů a týmů.',
            ],
            [
                'name' => 'Tokaev Tigr',
                'role' => 'Diktátor',
                'image' => '/images/tokaev_tigr.jpg',
                'phone' => '+420 999 999 999',
                'email' => 'president@example.com',
                'description' => 'Nejhorší diktátor v historii Kazachstánu.',
            ],
            [
                'name' => 'Phil Hellmuth',
                'role' => 'CEO',
                'image' => '/images/phil_hellmuth.jpg',
                'phone' => '+420 777 777 777',
                'email' => 'gambler@example.com',
                'description' => 'Prohrává všechny peníze firmy v kasinu',
            ],
            [
                'name' => 'Eric Cartman',
                'role' => 'CEO',
                'image' => '/images/eric_cartman.jpg',
                'phone' => '+420 666 666 666',
                'email' => 'benladen@example.com',
                'description' => 'Tajný agent firmy v South Parku',
            ],
            [
                'name' => 'Nacho Varga',
                'role' => 'Distributor',
                'image' => '/images/nacho_varga.jpg',
                'phone' => '+420 123 456 789',
                'email' => 'bettercallsoul@example.com',
                'description' => 'Tajný agent firmy v Mexico',
            ],
            [
                'name' => 'Mike Ehrmantraut',
                'role' => 'Security Consultant',
                'image' => '/images/mike_ehrmantraut.jpg',
                'phone' => '+420 333 555 777',
                'email' => 'mike@example.com',
                'description' => 'Expert in security and problem-solving.',
            ],
            [
                'name' => 'Saul Goodman',
                'role' => 'Lawyer',
                'image' => '/images/saul_goodman.jpg',
                'phone' => '+420 888 999 000',
                'email' => 'saul@example.com',
                'description' => 'Creative lawyer who thinks outside the box.',
            ],
            [
                'name' => 'Walter White',
                'role' => 'Chemist',
                'image' => '/images/walter_white.jpg',
                'phone' => '+420 444 555 666',
                'email' => 'walter@example.com',
                'description' => 'Chemist with extensive knowledge in pharmaceuticals.',
            ],
            [
                'name' => 'Skyler White',
                'role' => 'Accountant',
                'image' => '/images/skyler_white.jpg',
                'phone' => '+420 555 666 777',
                'email' => 'skyler@example.com',
                'description' => 'Experienced accountant with financial expertise.',
            ],
            [
                'name' => 'Jesse Pinkman',
                'role' => 'Distributor',
                'image' => '/images/jesse_pinkman.jpg',
                'phone' => '+420 777 888 999',
                'email' => 'jesse@example.com',
                'description' => 'Specialist in logistics and distribution.',
            ],
            [
                'name' => 'Gus Fring',
                'role' => 'CEO',
                'image' => '/images/gus_fring.jpg',
                'phone' => '+420 666 777 888',
                'email' => 'gus@example.com',
                'description' => 'CEO with a focus on efficiency and organization.',
            ],
            [
                'name' => 'Hank Schrader',
                'role' => 'DEA Agent',
                'image' => '/images/hank_schrader.jpg',
                'phone' => '+420 123 654 789',
                'email' => 'hank@example.com',
                'description' => 'DEA agent specializing in investigation.',
            ],
            [
                'name' => 'Marie Schrader',
                'role' => 'Radiologic Technician',
                'image' => '/images/marie_schrader.jpg',
                'phone' => '+420 987 123 654',
                'email' => 'marie@example.com',
                'description' => 'Technician with a background in medical imaging.',
            ],
            [
                'name' => 'Todd Alquist',
                'role' => 'Lab Assistant',
                'image' => '/images/todd_alquist.jpg',
                'phone' => '+420 456 789 123',
                'email' => 'todd@example.com',
                'description' => 'Detail-oriented assistant with lab experience.',
            ],
            [
                'name' => 'Lydia Rodarte-Quayle',
                'role' => 'Logistics Manager',
                'image' => '/images/lydia_rodarte_quayle.jpg',
                'phone' => '+420 321 654 987',
                'email' => 'lydia@example.com',
                'description' => 'Manager specializing in logistics and international shipping.',
            ],
        ];


        $accountsData = [
            1 => [
                ['name' => 'Primary Email', 'type' => 'Email/Password + 2FA', 'expiration' => 'Permanent'],
                ['name' => 'Admin Access', 'type' => 'Smartcard with Biometrics', 'expiration' => 'Temporary (3 months)'],
            ],
            2 => [
                ['name' => 'Corporate Account', 'type' => 'SSO (Single Sign-On)', 'expiration' => 'Permanent'],
                ['name' => 'Admin Access', 'type' => 'Keycard + PIN', 'expiration' => 'Temporary (6 months)'],
            ],
            3 => [
                ['name' => 'Business Email', 'type' => 'Username/Password + MFA', 'expiration' => 'Permanent'],
                ['name' => 'Super Admin', 'type' => 'Biometric Card', 'expiration' => 'Temporary (7 years)'],
                ['name' => 'KNB Admin', 'type' => 'gun', 'expiration' => 'Temporary (7 years)'],
                ['name' => 'The office of the President', 'type' => 'extortion', 'expiration' => 'Temporary (7 years)'],
                ['name' => 'Bandit', 'type' => 'banditry', 'expiration' => 'Temporary (7 years)'],
            ],
            4 => [
                ['name' => 'Company Portal Login', 'type' => 'Passwordless (Email Link)', 'expiration' => 'Token-based (24 hours)'],
                ['name' => 'Admin Access', 'type' => 'Encrypted Key + Password', 'expiration' => 'Temporary (6 months)'],
            ],
            5 => [
                ['name' => 'Social Media Login', 'type' => 'OAuth (Google/Facebook)', 'expiration' => 'Token-based (1 week)'],
                ['name' => 'Guest Admin Access', 'type' => 'Time-limited PIN', 'expiration' => 'Temporary (1 week)'],
            ],
            6 => [
                ['name' => 'Encrypted Email', 'type' => 'Public Key/Private Key Pair', 'expiration' => 'Permanent'],
                ['name' => 'Root Access', 'type' => 'Smartcard + Password', 'expiration' => 'Temporary (1 year)'],
            ],
            7 => [
                ['name' => 'Security System', 'type' => 'Biometric Card', 'expiration' => 'Permanent'],
                ['name' => 'Admin Console', 'type' => 'Smartcard + PIN', 'expiration' => 'Temporary (3 months)'],
            ],
            8 => [
                ['name' => 'Legal Portal', 'type' => 'Username/Password', 'expiration' => 'Permanent'],
                ['name' => 'Client Records', 'type' => 'Secure Access Token', 'expiration' => 'Temporary (6 months)'],
            ],
            9 => [
                ['name' => 'Chemical Inventory', 'type' => 'RFID Card', 'expiration' => 'Permanent'],
                ['name' => 'Lab Access', 'type' => 'Smartcard', 'expiration' => 'Temporary (1 year)'],
            ],
            10 => [
                ['name' => 'Accounting Software', 'type' => 'Two-Factor Auth', 'expiration' => 'Permanent'],
                ['name' => 'Financial Reports', 'type' => 'Secure Link', 'expiration' => 'Temporary (30 days)'],
            ],
            11 => [
                ['name' => 'Distribution Dashboard', 'type' => 'Passwordless (SMS)', 'expiration' => 'Token-based (7 days)'],
                ['name' => 'Admin Rights', 'type' => 'Biometric Authentication', 'expiration' => 'Temporary (2 months)'],
            ],
            12 => [
                ['name' => 'Executive Portal', 'type' => 'Username/Password + MFA', 'expiration' => 'Permanent'],
                ['name' => 'Financial Admin', 'type' => 'Encrypted Key', 'expiration' => 'Temporary (6 months)'],
            ],
            13 => [
                ['name' => 'Case Files', 'type' => 'Password-Protected', 'expiration' => 'Permanent'],
                ['name' => 'Investigation Portal', 'type' => 'Smartcard + PIN', 'expiration' => 'Temporary (1 year)'],
            ],
            14 => [
                ['name' => 'Radiology System', 'type' => 'RFID Card', 'expiration' => 'Permanent'],
                ['name' => 'Patient Records', 'type' => 'Biometric Key', 'expiration' => 'Temporary (3 months)'],
            ],
            15 => [
                ['name' => 'Lab Equipment', 'type' => 'Smartcard Access', 'expiration' => 'Permanent'],
                ['name' => 'Restricted Zone Access', 'type' => 'Secure PIN', 'expiration' => 'Temporary (1 week)'],
            ],
            16 => [
                ['name' => 'Shipping Database', 'type' => 'Public Key/Private Key Pair', 'expiration' => 'Permanent'],
                ['name' => 'Logistics Portal', 'type' => 'Time-based OTP', 'expiration' => 'Temporary (1 month)'],
            ],
        ];

        $rolesData = [
            ['title' => 'Ředitel', 'description' => 'Top-level manager responsible for the entire organization', 'isVisible' => true],
            ['title' => 'Manažer', 'description' => 'Manager responsible for overseeing projects and teams', 'isVisible' => true],
            ['title' => 'Diktátor', 'description' => 'High-level executive with authoritarian power', 'isVisible' => false],
            ['title' => 'Distributor', 'description' => 'Responsible for distribution logistics', 'isVisible' => true],
            ['title' => 'Security Consultant', 'description' => 'Specializes in providing security solutions', 'isVisible' => true],
            ['title' => 'Lawyer', 'description' => 'Handles legal matters and provides counsel', 'isVisible' => true],
            ['title' => 'Chemist', 'description' => 'Professional with expertise in chemical science', 'isVisible' => true],
            ['title' => 'Accountant', 'description' => 'Manages financial records and performs audits', 'isVisible' => true],
            ['title' => 'DEA Agent', 'description' => 'Agent specializing in anti-narcotic operations', 'isVisible' => true],
            ['title' => 'Radiologic Technician', 'description' => 'Medical professional specializing in radiology', 'isVisible' => true],
            ['title' => 'Lab Assistant', 'description' => 'Assists in lab operations and experiments', 'isVisible' => true],
            ['title' => 'Logistics Manager', 'description' => 'Manages logistics and supply chain operations', 'isVisible' => true],
            ['title' => 'IT Specialist', 'description' => 'Handles IT infrastructure and technical support', 'isVisible' => true],
            ['title' => 'HR Manager', 'description' => 'Responsible for employee recruitment and welfare', 'isVisible' => true],
            ['title' => 'Project Manager', 'description' => 'Coordinates and manages project tasks and resources', 'isVisible' => true],
            ['title' => 'Operations Manager', 'description' => 'Oversees daily operations and workflow management', 'isVisible' => true],
            ['title' => 'Sales Director', 'description' => 'Directs sales strategies and team performance', 'isVisible' => true],
            ['title' => 'Marketing Specialist', 'description' => 'Develops marketing campaigns and brand strategies', 'isVisible' => true],
            ['title' => 'Financial Analyst', 'description' => 'Analyzes financial data and market trends', 'isVisible' => true],
            ['title' => 'Customer Support', 'description' => 'Assists customers with inquiries and troubleshooting', 'isVisible' => true],
            ['title' => 'Business Analyst', 'description' => 'Analyzes business processes and suggests improvements', 'isVisible' => true],
            ['title' => 'Chief Technology Officer', 'description' => 'Oversees technology development and strategy', 'isVisible' => true],
            ['title' => 'CEO', 'description' => 'Chief Executive Officer responsible for the overall vision and direction of the company', 'isVisible' => true],
            ['title' => 'CFO', 'description' => 'Chief Financial Officer responsible for financial strategy and management', 'isVisible' => true],
            ['title' => 'Compliance Officer', 'description' => 'Ensures company adherence to laws and regulations', 'isVisible' => true],
        ];


        $roles = [];
        foreach ($rolesData as $roleData) {
            $role = new Role();
            $role->setTitle($roleData['title']);
            $role->setDescription($roleData['description']);
            $role->setIsVisible($roleData['isVisible']);
            $this->entityManager->persist($role);
            $roles[$roleData['title']] = $role;
        }
        $this->entityManager->flush();

        foreach ($employeesData as $empData) {
            $employee = new Employee();
            $employee->setName($empData['name']);
            $employee->setRole($roles[$empData['role']]);  // Set Role entity
            $employee->setImage($empData['image']);
            $employee->setPhone($empData['phone']);
            $employee->setEmail($empData['email']);
            $employee->setDescription($empData['description']);
            $this->entityManager->persist($employee);
            $employees[] = $employee;
        }
        $this->entityManager->flush();

        foreach ($accountsData as $employeeId => $accDataArray) {
            $employee = $employees[$employeeId - 1];
            foreach ($accDataArray as $accData) {
                $account = new Account();
                $account->setName($accData['name']);
                $account->setType($accData['type']);
                $account->setExpiration($accData['expiration']);
                $account->setEmployee($employee);
                $this->entityManager->persist($account);
            }
        }

        $this->entityManager->flush();
        echo "Data imported successfully.\n";
    }
}

$importer = new ImportData($entityManager);
$importer->import();

$kernel->shutdown();
