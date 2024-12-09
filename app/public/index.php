<?php

use App\App;
use Dotenv\Dotenv;

const DS = DIRECTORY_SEPARATOR;
// Chemin physique vers le dossier racine
define( 'ROOT_PATH', dirname(__FILE__, 2). DS );
// Chemin physique vers le dossier "src"
define( 'APP_PATH', ROOT_PATH .'src'. DS );

// Chargement du système d'autoload
require_once ROOT_PATH .'vendor'. DS .'autoload.php';

// Chargement du module .env
Dotenv::createImmutable( ROOT_PATH )->load();

App::getApp()->start();