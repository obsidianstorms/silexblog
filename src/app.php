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
$app->get('/register', 'BasicBlog\Page\Page::register');
$app->post('/register', 'BasicBlog\Page\Page::newAuthor');

$app->post('/login', 'BasicBlog\Page\Page::login');
$app->post('/post', 'BasicBlog\Page\Page::newPost');
$app->get('/{post_id}', 'BasicBlog\Page\Page::viewPost');
$app->get('/{post_id}/edit', 'BasicBlog\Page\Page::editPost');
$app->put('/{post_id}', 'BasicBlog\Page\Page::changePost');
$app->delete('/{post_id}', 'BasicBlog\Page\Page::removePost');
$app->post('/{post_id}/comment', 'BasicBlog\Page\Page::newComment');
$app->get('/{post_id}/{comment_id}', 'BasicBlog\Page\Page::viewComment');
$app->delete('/{post_id}/{comment_id}', 'BasicBlog\Page\Page::removeComment');

//$app->match('/', 'BasicBlog\Page::index')
//->method('GET|POST');



return $app;
