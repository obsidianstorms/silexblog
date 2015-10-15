<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

/** Debug toggle to see errors in browser */
$app['debug'] = true;

/** Local config values */
list($dbname, $dbuser, $dbpass) = include_once(__DIR__ . '/../config/config.local.php');

/** Register providers */
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
   'db.options' => array(
       'driver' => 'pdo_mysql',
       'dbname' => $dbname,
       'host' => 'localhost',
       'user' => $dbuser,
       'password' => $dbpass,
       'charset' => 'utf8',
   )
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../logs/dev.log',
));


/** Routes */
$app->match('/', function () use ($app) {
    $app['monolog']->addInfo('logging example');
    return 'here';
})
->method('GET|POST');

return $app;
