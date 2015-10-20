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
$app->get('/login', 'BasicBlog\Page\Page::pickLogin');
$app->get('/logout', 'BasicBlog\Page\Page::logout');
$app->get('/author/login', 'BasicBlog\Page\Page::viewLoginAuthor');
$app->get('/commentator/login', 'BasicBlog\Page\Page::viewLoginCommentator');
$app->get('/author/register', 'BasicBlog\Page\Page::viewRegisterAuthor');
$app->get('/commentator/register', 'BasicBlog\Page\Page::viewRegisterCommentator');
$app->get('/post/{post_id}/comment/login', 'BasicBlog\Page\Page::viewLoginComment');
$app->get('/post/{post_id}/comment/register', 'BasicBlog\Page\Page::viewRegisterComment');
$app->get('/post/{post_id}', 'BasicBlog\Page\Page::viewPost');
$app->get('/post/{post_id}/edit', 'BasicBlog\Page\Page::viewEditPost');

$app->post('/author/login', 'BasicBlog\Page\Page::validateLoginAuthor');
$app->post('/commentator/login', 'BasicBlog\Page\Page::validateLoginCommentator');
$app->post('/author/register', 'BasicBlog\Page\Page::newAuthor');
$app->post('/commentator/register', 'BasicBlog\Page\Page::newCommentator');
$app->post('/post/{post_id}/comment/login', 'BasicBlog\Page\Page::validateLoginCommentator');
$app->post('/post/{post_id}/comment/register', 'BasicBlog\Page\Page::newCommentator');
$app->post('/post/add', 'BasicBlog\Page\Page::newPost');
$app->post('/post/{post_id}/comment/add', 'BasicBlog\Page\Page::newComment');

$app->put('/post/{post_id}', 'BasicBlog\Page\Page::changePost');

$app->delete('/post/{post_id}/delete', 'BasicBlog\Page\Page::removePost');
$app->delete('/post/{post_id}/comment/{comment_id}/delete', 'BasicBlog\Page\Page::removeComment');


//$app->match('/', 'BasicBlog\Page::index')
//->method('GET|POST');

return $app;
