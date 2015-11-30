<?php
use app\core\App;

require_once '../app/Config.php';
require_once '../app/AutoLoad.php';

// default controller and its action
define('DEFAULT_CONTROLLER', 'HomeController');
define('DEFAULT_ACTION', 'indexAction');
define('DEFAULT_ROUTE', 'home');

// db connection params
define('DB_DRIVER', 'MySQLiDriver');
define ("DB_CONN_PARAMS", serialize (array(
	'host' => 'localhost',
	'username' => 'root',
	'password' => '',
    'db' => 'snp'
)));

// adding routes to global Scope
$app = new App();
$app->addRoute('home', 'Home', false);
$app->addRoute('register', 'Register', false);
$app->addRoute('login', 'Login', false);
$app->addRoute('news', 'News', false);
$app->addRoute('categories', 'Category', false);
$app->addRoute('comments', 'Comment', false);
$app->addRoute('logout', 'Logout', false);
$app->run();