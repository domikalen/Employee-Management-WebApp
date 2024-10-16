use Twig\Environment;
use Twig\Loader\FilesystemLoader;
<?php
require __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Router;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$router = new Router($twig);
$router->route();
