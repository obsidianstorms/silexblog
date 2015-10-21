<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

/** Debug toggle to see errors in browser */
$app['debug'] = true;

/** Local config values */
$settings = include __DIR__ . '/../config/config.local.php';

/** Register providers */
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
   'db.options' => array(
       'driver' => 'pdo_mysql',
       'dbname' => $settings['dbname'],
       'host' => 'localhost',
       'user' => $settings['user'],
       'password' => $settings['password'],
       'charset' => 'utf8',
   )
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../logs/dev.log',
));
$app->register(new Silex\Provider\SessionServiceProvider());


/** Routes */
$app->get('/', 'BasicBlog\Page\Page::index');
$app->get('/logout', 'BasicBlog\Page\Page::logout');
$app->get('/login/{user}', 'BasicBlog\Page\Page::viewLogin');
$app->get('/register/{user}', 'BasicBlog\Page\Page::viewRegister');

$app->get('/post/{post_id}', 'BasicBlog\Page\Page::viewReadPost');
$app->get('/post/{post_id}/edit', 'BasicBlog\Page\Page::viewEditPost');

$app->post('/login/{user}', 'BasicBlog\Page\Page::validateLogin');
$app->post('/register/{user}', 'BasicBlog\Page\Page::newUser');

$app->post('/post/add', 'BasicBlog\Page\Page::newPost');
$app->post('/post/{post_id}/edit', 'BasicBlog\Page\Page::editPost');
$app->get('/post/{post_id}/delete', 'BasicBlog\Page\Page::removePost');

$app->post('/post/{post_id}/comment/add', 'BasicBlog\Page\Page::newComment');
$app->get('/post/{post_id}/comment/{comment_id}/delete', 'BasicBlog\Page\Page::removeComment');

return $app;
