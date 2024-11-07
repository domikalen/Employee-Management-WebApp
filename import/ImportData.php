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
                'roles' => ['Ředitel'],
                'image' => '/images/karlos_huares.jpg',
                'phone' => '+420 123 456 780',
                'email' => 'karlos@example.com',
                'description' => 'Karlos je odborný manažer, který se soustředí na optimalizaci výkonnosti týmu.',
            ],
            [
                'name' => 'Richard Gere',
                'roles' => ['Manažer'],
                'image' => '/images/richard_gere.jpg',
                'phone' => '+420 987 654 321',
                'email' => 'richard@example.com',
                'description' => 'Richard je zodpovědný za správu projektů a týmů.',
            ],
            [
                'name' => 'Tokaev Tigr',
                'roles' => ['Diktátor'],
                'image' => '/images/tokaev_tigr.jpg',
                'phone' => '+420 999 999 999',
                'email' => 'president@example.com',
                'description' => 'Nejhorší diktátor v historii Kazachstánu.',
            ],
            [
                'name' => 'Phil Hellmuth',
                'roles' => ['CEO'],
                'image' => '/images/phil_hellmuth.jpg',
                'phone' => '+420 777 777 777',
                'email' => 'gambler@example.com',
                'description' => 'Prohrává všechny peníze firmy v kasinu',
            ],
            [
                'name' => 'Eric Cartman',
                'roles' => ['CEO'],
                'image' => '/images/eric_cartman.jpg',
                'phone' => '+420 666 666 666',
                'email' => 'benladen@example.com',
                'description' => 'Tajný agent firmy v South Parku',
            ],
            [
                'name' => 'Nacho Varga',
                'roles' => ['Distributor'],
                'image' => '/images/nacho_varga.jpg',
                'phone' => '+420 123 456 789',
                'email' => 'bettercallsoul@example.com',
                'description' => 'Tajný agent firmy v Mexico',
            ],
            [
                'name' => 'Mike Ehrmantraut',
                'roles' => ['Security Consultant'],
                'image' => '/images/mike_ehrmantraut.jpg',
                'phone' => '+420 333 555 777',
                'email' => 'mike@example.com',
                'description' => 'Expert in security and problem-solving.',
            ],
            [
                'name' => 'Saul Goodman',
                'roles' => ['Lawyer'],
                'image' => '/images/saul_goodman.jpg',
                'phone' => '+420 888 999 000',
                'email' => 'saul@example.com',
                'description' => 'Creative lawyer who thinks outside the box.',
            ],
            [
                'name' => 'Walter White',
                'roles' => ['Chemist'],
                'image' => '/images/walter_white.jpg',
                'phone' => '+420 444 555 666',
                'email' => 'walter@example.com',
                'description' => 'Chemist with extensive knowledge in pharmaceuticals.',
            ],
            [
                'name' => 'Skyler White',
                'roles' => ['Accountant'],
                'image' => '/images/skyler_white.jpg',
                'phone' => '+420 555 666 777',
                'email' => 'skyler@example.com',
                'description' => 'Experienced accountant with financial expertise.',
            ],
            [
                'name' => 'Jesse Pinkman',
                'roles' => ['Distributor'],
                'image' => '/images/jesse_pinkman.jpg',
                'phone' => '+420 777 888 999',
                'email' => 'jesse@example.com',
                'description' => 'Specialist in logistics and distribution.',
            ],
            [
                'name' => 'Gus Fring',
                'roles' => ['CEO'],
                'image' => '/images/gus_fring.jpg',
                'phone' => '+420 666 777 888',
                'email' => 'gus@example.com',
                'description' => 'CEO with a focus on efficiency and organization.',
            ],
            [
                'name' => 'Hank Schrader',
                'roles' => ['DEA Agent'],
                'image' => '/images/hank_schrader.jpg',
                'phone' => '+420 123 654 789',
                'email' => 'hank@example.com',
                'description' => 'DEA agent specializing in investigation.',
            ],
            [
                'name' => 'Marie Schrader',
                'roles' => ['Radiologic Technician'],
                'image' => '/images/marie_schrader.jpg',
                'phone' => '+420 987 123 654',
                'email' => 'marie@example.com',
                'description' => 'Technician with a background in medical imaging.',
            ],
            [
                'name' => 'Todd Alquist',
                'roles' => ['Lab Assistant'],
                'image' => '/images/todd_alquist.jpg',
                'phone' => '+420 456 789 123',
                'email' => 'todd@example.com',
                'description' => 'Detail-oriented assistant with lab experience.',
            ],
            [
                'name' => 'Lydia Rodarte-Quayle',
                'roles' => ['Logistics Manager'],
                'image' => '/images/lydia_rodarte_quayle.jpg',
                'phone' => '+420 321 654 987',
                'email' => 'lydia@example.com',
                'description' => 'Manager specializing in logistics and international shipping.',
            ],
        ];


        $accountsData = [
            1 => [
                ['name' => 'Primary Email', 'type' => 'Email/Password + 2FA', 'expiration' => '2025-01-01 23:59:59'],
                ['name' => 'Admin Access', 'type' => 'Smartcard with Biometrics', 'expiration' => '2024-03-01 23:59:59'],
            ],
            2 => [
                ['name' => 'Corporate Account', 'type' => 'SSO (Single Sign-On)', 'expiration' => '2025-06-30 23:59:59'],
                ['name' => 'Admin Access', 'type' => 'Keycard + PIN', 'expiration' => '2024-12-01 23:59:59'],
            ],
            3 => [
                ['name' => 'Business Email', 'type' => 'Username/Password + MFA', 'expiration' => '2026-05-01 23:59:59'],
                ['name' => 'Super Admin', 'type' => 'Biometric Card', 'expiration' => '2030-07-01 23:59:59'],
                ['name' => 'KNB Admin', 'type' => 'gun', 'expiration' => '2030-07-01 23:59:59'],
                ['name' => 'The office of the President', 'type' => 'extortion', 'expiration' => '2030-07-01 23:59:59'],
                ['name' => 'Bandit', 'type' => 'banditry', 'expiration' => '2030-07-01 23:59:59'],
            ],
            4 => [
                ['name' => 'Company Portal Login', 'type' => 'Passwordless (Email Link)', 'expiration' => '2024-01-15 23:59:59'],
                ['name' => 'Admin Access', 'type' => 'Encrypted Key + Password', 'expiration' => '2024-08-01 23:59:59'],
            ],
            5 => [
                ['name' => 'Social Media Login', 'type' => 'OAuth (Google/Facebook)', 'expiration' => '2024-02-10 23:59:59'],
                ['name' => 'Guest Admin Access', 'type' => 'Time-limited PIN', 'expiration' => '2024-02-10 23:59:59'],
            ],
            6 => [
                ['name' => 'Encrypted Email', 'type' => 'Public Key/Private Key Pair', 'expiration' => '2025-01-01 23:59:59'],
                ['name' => 'Root Access', 'type' => 'Smartcard + Password', 'expiration' => '2025-12-01 23:59:59'],
            ],
            7 => [
                ['name' => 'Security System', 'type' => 'Biometric Card', 'expiration' => '2026-01-01 23:59:59'],
                ['name' => 'Admin Console', 'type' => 'Smartcard + PIN', 'expiration' => '2024-04-01 23:59:59'],
            ],
            8 => [
                ['name' => 'Legal Portal', 'type' => 'Username/Password', 'expiration' => '2025-01-01 23:59:59'],
                ['name' => 'Client Records', 'type' => 'Secure Access Token', 'expiration' => '2024-09-01 23:59:59'],
            ],
            9 => [
                ['name' => 'Chemical Inventory', 'type' => 'RFID Card', 'expiration' => '2025-06-01 23:59:59'],
                ['name' => 'Lab Access', 'type' => 'Smartcard', 'expiration' => '2025-03-01 23:59:59'],
            ],
            10 => [
                ['name' => 'Accounting Software', 'type' => 'Two-Factor Auth', 'expiration' => '2025-11-01 23:59:59'],
                ['name' => 'Financial Reports', 'type' => 'Secure Link', 'expiration' => '2024-03-01 23:59:59'],
            ],
            11 => [
                ['name' => 'Distribution Dashboard', 'type' => 'Passwordless (SMS)', 'expiration' => '2024-01-20 23:59:59'],
                ['name' => 'Admin Rights', 'type' => 'Biometric Authentication', 'expiration' => '2024-05-01 23:59:59'],
            ],
            12 => [
                ['name' => 'Executive Portal', 'type' => 'Username/Password + MFA', 'expiration' => '2026-06-01 23:59:59'],
                ['name' => 'Financial Admin', 'type' => 'Encrypted Key', 'expiration' => '2024-10-01 23:59:59'],
            ],
            13 => [
                ['name' => 'Case Files', 'type' => 'Password-Protected', 'expiration' => '2025-01-01 23:59:59'],
                ['name' => 'Investigation Portal', 'type' => 'Smartcard + PIN', 'expiration' => '2025-05-01 23:59:59'],
            ],
            14 => [
                ['name' => 'Radiology System', 'type' => 'RFID Card', 'expiration' => '2025-09-01 23:59:59'],
                ['name' => 'Patient Records', 'type' => 'Biometric Key', 'expiration' => '2024-03-01 23:59:59'],
            ],
            15 => [
                ['name' => 'Lab Equipment', 'type' => 'Smartcard Access', 'expiration' => '2026-01-01 23:59:59'],
                ['name' => 'Restricted Zone Access', 'type' => 'Secure PIN', 'expiration' => '2024-01-15 23:59:59'],
            ],
            16 => [
                ['name' => 'Shipping Database', 'type' => 'Public Key/Private Key Pair', 'expiration' => '2026-02-01 23:59:59'],
                ['name' => 'Logistics Portal', 'type' => 'Time-based OTP', 'expiration' => '2024-05-01 23:59:59'],
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
            ['title' => 'Officer', 'description' => 'lol', 'isVisible' => true],

        ];


        $roles = [];
        foreach ($rolesData as $roleData) {
            $existingRole = $this->entityManager->getRepository(Role::class)->findOneBy(['title' => $roleData['title']]);

            if (!$existingRole) {
                $role = new Role();
                $role->setTitle($roleData['title']);
                $role->setDescription($roleData['description']);
                $role->setIsVisible($roleData['isVisible']);
                $this->entityManager->persist($role);
                $roles[$roleData['title']] = $role;
            } else {
                $roles[$roleData['title']] = $existingRole;
            }
        }
        $this->entityManager->flush();

        foreach ($employeesData as $empData) {
            $existingEmployee = $this->entityManager->getRepository(Employee::class)->findOneBy(['phone' => $empData['phone']]);

            if ($existingEmployee) {
                echo "Employee with phone " . $empData['phone'] . " already exists. Skipping import for this employee.\n";
                continue;
            }

            $employee = new Employee();
            $employee->setName($empData['name']);
            $employee->setImage($empData['image']);
            $employee->setPhone($empData['phone']);
            $employee->setEmail($empData['email']);
            $employee->setDescription($empData['description']);

            $empRoles = $empData['roles'] ?? [];
            foreach ($empRoles as $roleTitle) {
                if (isset($roles[$roleTitle])) {
                    $employee->addRole($roles[$roleTitle]);
                }
            }

            $this->entityManager->persist($employee);
            $employees[] = $employee;
        }

        $this->entityManager->flush();

        // The rest of the accounts import logic remains the same
        foreach ($accountsData as $employeeId => $accDataArray) {
            $employee = $employees[$employeeId - 1];
            foreach ($accDataArray as $accData) {
                $account = new Account();
                $account->setName($accData['name']);
                $account->setType($accData['type']);

                $expirationDate = \DateTime::createFromFormat('Y-m-d H:i:s', $accData['expiration']);
                $account->setExpiration($expirationDate);

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
