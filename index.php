<?php
error_reporting(~0);
use \Twelve\AutoloaderManager\AutoloaderManager;
use \Twelve\Router;
use \Twelve\Http;
use \Twelve\Session;
use \Twelve\Database;
use \PDO;
require_once 'Library/Twelve/AutoloaderManager/AutoloaderManager.php';
// few settings
$settings =
        [
        'sessionLifetime' => '60',
        'gcProbability' => '100',
        'gcDivisor' => '200',
        'securityCode' => 'SD&*$&@#sadux&D@3'
        ];
$dbSettings =
    [
    'dsn' => 'mysql:dbname=Twelve;host=localhost',
    'username' => 'root',
    'password' => 'aspirina',
    'lockTimeOut' => '50'
    ];
// register our autoloaders
$autoloadManager = new AutoloaderManager('Kernel', 'Library');
$autoloadManager->register();
$autoloadManager = new AutoloaderManager('Twelve', 'Library');
$autoloadManager->register();
$autoloadManager = new AutoloaderManager('SiteName', 'Application');
$autoloadManager->register();
$autoloadManager = new AutoloaderManager('Twig', 'Library/Twelve');
$autoloadManager->setNamespaceSeparator('_');
$autoloadManager->register();
// setup request
$request = new Http\Request($_SERVER, $_COOKIE, $_POST, $_GET, $_REQUEST);
// make sure session cookies never expire so that session lifetime
ini_set('session.cookie_lifetime', 0);
// if $session_lifetime is specified and is an integer number
ini_set('session.gc_maxlifetime', $settings['sessionLifetime']);
// if $gc_probability is specified and is an integer number
ini_set('session.gc_probability', $settings['gcProbability']);
// if $gc_divisor is specified and is an integer number
ini_set('session.gc_divisor', $settings['gcDivisor']);
// initiate session class
//$pdo = new PDO($dbSettings['dsn'], $dbSettings['username'], $dbSettings['password']);
//$session = Session\Factory::getPdoBased($request, $pdo, $settings);
// set session handler
//session_set_save_handler($session, true);
// start the session
//session_start();
//$db = new Database\Database($dbSettings);
$routeCollection = new Router\RouterCollection();
$routeCollection->add('/', '/', 'SiteName:Controller:Index', 'Home');
$routeCollection->add('test', '/index.php', 'SiteName:Controller:Index', 'Home');
$routeCollection->add('default', '/index.php/', 'SiteName:Controller:Index', 'Home');
$routeCollection->add('index', '/index.php/index/home/{name}', 'SiteName:Controller:Index', 'Home', ['name' => '(\w+)']);
$routeCollection->add('404', '', 'SiteName:Controller:NotFound', 'Error');
if($routeCollection->findMatch($request->uri()))
    $dispatch = new Router\Router;
    $dispatch->dispatch($routeCollection->findMatch($request->uri()), $request->uri());
echo '<br />Executed in ', round(microtime(true) - $request->server('REQUEST_TIME_FLOAT'), 2), ' Seconds';
