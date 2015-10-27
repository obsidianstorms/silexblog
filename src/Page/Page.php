<?php

namespace BasicBlog\Page;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BasicBlog\Post\PostApi;
use BasicBlog\Post\PostData;
use BasicBlog\Author\AuthorApi;
use BasicBlog\Author\AuthorData;
use BasicBlog\Comment\CommentApi;
use BasicBlog\Comment\CommentData;
use BasicBlog\Security\Password;
use BasicBlog\Commentator\CommentatorApi;
use BasicBlog\Commentator\CommentatorFactory;
use BasicBlog\Commentator\CommentatorData;


/**
 * Class Page
 *
 * Controller for route status
 */
class Page
{
    /**
     * @var string Exception catching message
     */
    const MESSAGE_CAUGHT_EXCEPTION = 'Caught exception message [%s] with code [%s].';

    /**
     * @param Application $app
     *
     * @return bool
     */
    protected function isLoggedIn(Application $app)
    {
        if (is_null($app['session']->get('author'))
            && is_null($app['session']->get('commentator'))
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param Application $app
     *
     * @return bool
     */
    protected function isAdmin(Application $app)
    {
        if (is_null($app['session']->get('author'))) {
            return false;
        }
        return true;
    }

    /**
     * @param Application $app
     *
     * @return bool
     */
    protected function isUser(Application $app)
    {
        if (is_null($app['session']->get('commentator'))) {
            return false;
        }
        return true;
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function index(Application $app)
    {
        // preset
        $requestResponseCode = Response::HTTP_OK;

        // Fetch Post list
        $apiObject = new PostApi(new PostData($app));
        try {
            $result = $apiObject->fetchAll($app);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            $requestResponseCode = Response::HTTP_BAD_REQUEST;
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            $requestResponseCode = Response::HTTP_BAD_REQUEST;
        }

        // Render page sections
        $pageArgs = [
            'loggedIn' => $this->isLoggedIn($app),
            'admin' => $this->isAdmin($app),
            'message' => false,
            'addPost' => false,
            'posts' => false,
        ];

        if (isset($message)) {
            $app['session']->getFlashBag()->add('message', $message);
        }

        //todo authorship display
        if ($this->isAdmin($app)) {
            $pageArgs['addPost'] = true;
        }

        if (isset($result) && is_array($result)) {
            $pageArgs['posts'] = $result;
        }

        // Return page
        $content = $app['twig']->render('index.twig', $pageArgs);
        return new Response($content, $requestResponseCode);

        //todo: collection of objects with individual authorship rather than just array loop
    }

    /**
     * @param Application $app
     * @param $user string
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function viewLogin(Application $app, $user)
    {
        $isLoggedIn = $this->isLoggedIn($app);

        if ($isLoggedIn) {
            $app['session']->getFlashBag()->add('message', 'Already logged in.');
//            return $app->redirect('/', Response::HTTP_METHOD_NOT_ALLOWED);
            $this->index($app);
        }

        // Render page sections
        $pageArgs = [
            'loggedIn' => $isLoggedIn,
            'form' => false,
        ];

        switch ($user) {
            case 'author':
                $pageArgs['form'] = 'sections/form.login.author.twig';
                break;
            case 'commentator':
                //todo: login from post comment
                $pageArgs['form'] = 'sections/form.login.commentator.twig';
                break;
            default:
                $app['session']->getFlashBag()->add('message', 'Unknown user login form render error.');
                $this->index($app);
        }

        // Return page
        $content = $app['twig']->render('login.twig', $pageArgs);
        return new Response($content, Response::HTTP_OK);
    }

    /**
     * @param Application $app
     * @param $user string
     *
     * @return Response
     */
    public function viewRegister(Application $app, $user)
    {
        $isLoggedIn = $this->isLoggedIn($app);

        if ($isLoggedIn) {
            $app['session']->getFlashBag()->add('message', 'Already logged in.');
            return $this->index($app);
        }

        // Render page sections
        $pageArgs = [
            'loggedIn' => $isLoggedIn,
            'form' => false,
        ];

        switch ($user) {
            case 'author':
                $pageArgs['form'] = 'sections/form.register.author.twig';
                break;
            case 'commentator':
                //todo: register from post comment
                $pageArgs['form'] = 'sections/form.register.commentator.twig';
                break;
            default:
                $app['session']->getFlashBag()->add('message', 'Unknown user registration form render error.');
                return $this->index($app);
        }

        // Return page
        $content = $app['twig']->render('register.twig', $pageArgs);
        return new Response($content);
    }

    /**
     * @param Application $app
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function logout(Application $app)
    {
        if ($this->isAdmin($app)) {
            $user = new AuthorApi(new AuthorData($app));
        } elseif ($this->isUser($app)) {
            $user = new CommentatorApi(new CommentatorData($app));
        } else {
            $app['monolog']->addError('Session found but not author nor commentator.');
            $app['session']->getFlashBag()->add('message', 'Unknown logout request made.');
            return $this->index($app);
        }
        $user->logout();

        $app['session']->getFlashBag()->add('message', 'Successfully logged out.');
        return $this->index($app);
    }

    /**
     * @param Application $app
     * @param $post_id
     *
     * @return Response
     */
    public function viewReadPost(Application $app, $post_id)
    {
        $requestResponseCode = Response::HTTP_OK;

        // Filter and validation
        $id = filter_var($post_id, FILTER_VALIDATE_INT);
        if ($id === false) {
            $message = 'Queried id must be a number.';
            $app['monolog']->addError('Integer filtering returned false. ' . $message);
            $requestResponseCode = Response::HTTP_BAD_REQUEST;
        }

        // Fetch post data
        $apiObject = new PostApi(new PostData($app));
        try {
            $post = $apiObject->fetch($id);
            if (!$post) {
                $requestResponseCode = Response::HTTP_BAD_REQUEST;
            }
        } catch (\InvalidArgumentException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            $message = 'Invalid query.';
            $requestResponseCode = Response::HTTP_BAD_REQUEST;
        } catch (\UnexpectedValueException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            $message = 'Failed to retrieve data.';
            $requestResponseCode = Response::HTTP_BAD_REQUEST;
        }

        // Fetch comment data
        $apiCommentObject = new CommentApi(new CommentData($app));
        try {
            $comments = $apiCommentObject->fetchAll($post['post_id'], new CommentatorFactory($app));
            //todo: need authorship for comments
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
            $requestResponseCode = Response::HTTP_BAD_REQUEST;
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
            $requestResponseCode = Response::HTTP_BAD_REQUEST;
        }

        // Content Display

        // Render page sections
        $pageArgs = [
            'loggedIn' => $this->isLoggedIn($app),
            'admin' => $this->isAdmin($app),
            'message' => false,
            'post' => false,
            'comments' => false,
            'addComment' => false,
        ];

        if (isset($message)) {
            $app['session']->getFlashBag()->add('message', $message);
        }

        //todo authorship display
        if (!is_null($app['session']->get('commentator'))) {
            $pageArgs['addComment'] = true;
        }

        if (isset($post) && is_array($post)) {
            $pageArgs['post'] = $post;
            $pageArgs['post_id'] = $post['post_id'];
        }

        if (isset($comments) && is_array($comments)) {
            $pageArgs['comments'] = $comments;
        }

        // Return page
        $content = $app['twig']->render('post.twig', $pageArgs);
        return new Response($content, $requestResponseCode);
    }

    /**
     * @param $app Application
     * @param $post_id integer
     *
     * @return Response
     */
    public function viewEditPost(Application $app, $post_id)
    {
        $requestResponseCode = Response::HTTP_OK;

        // Filter and validation
        $id = filter_var($post_id, FILTER_VALIDATE_INT);
        if ($id === false) {
            $message = 'Queried id must be a number.';
            $app['monolog']->addError('Integer filtering returned false. ' . $message);
            $requestResponseCode = Response::HTTP_BAD_REQUEST;
        }

        // Fetch post data
        $apiObject = new PostApi(new PostData($app));
        try {
            $post = $apiObject->fetch($id);
            if (!$post) {
                $requestResponseCode = Response::HTTP_BAD_REQUEST;
                $message = 'Failed to find post data.';
            }
        } catch (\InvalidArgumentException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            $message = 'Invalid query.';
            $requestResponseCode = Response::HTTP_BAD_REQUEST;
        } catch (\UnexpectedValueException $e) {
            $app['monolog']->addError(
                sprintf(
                    static::MESSAGE_CAUGHT_EXCEPTION,
                    $e->getMessage(),
                    $e->getCode()
                )
            );
            $message = 'Failed to retrieve data.';
            $requestResponseCode = Response::HTTP_BAD_REQUEST;
        }

        // Render page sections
        $pageArgs = [
            'loggedIn' => $this->isLoggedIn($app),
            'admin' => $this->isAdmin($app),
            'message' => false,
            'post' => false,
        ];

        if (isset($message)) {
            $app['session']->getFlashBag()->add('message', $message);
        }

        if (isset($post) && is_array($post)) {
            $pageArgs['post'] = $post;
        }

        $content = $app['twig']->render('post.edit.twig', $pageArgs);
        return new Response($content, $requestResponseCode);
    }

    /**
     * @param Application $app
     * @param $user string
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newUser(Application $app, $user)
    {
        switch ($user) {
            case 'author':
                $apiObject = new AuthorApi(new AuthorData($app));
                $resultFalseMessage = 'Found an existing author, unable to register another.';
                $resultTrueMessage = 'Added author.';
                break;
            case 'commentator':
                $apiObject = new CommentatorApi(new CommentatorData($app));
                $resultFalseMessage = 'Failed registering commentary user. Reason: unknown.';
                $resultTrueMessage = 'Successfully registered to comment.';
                break;
            default:
                $app['session']->getFlashBag()->add('message', 'Unknown user creation attempt.');
                return $this->index($app);
        }

        $apiObject->setPasswordObject(new Password());

        try {
            $result = $apiObject->create($_POST);
        } catch (\InvalidArgumentException $e) {
            $app['session']->getFlashBag()->add('message', $e->getMessage());
            return $this->viewRegister($app, $user);
        } catch (\UnexpectedValueException $e) {
            $app['session']->getFlashBag()->add('message', $e->getMessage());
            return $this->viewRegister($app, $user);
        }

        if (!isset($result) || !$result) {
            $app['session']->getFlashBag()->add('message', $resultFalseMessage);
            return $this->index($app);
        }
        $app['session']->getFlashBag()->add('message', $resultTrueMessage);
        return $this->index($app);
    }

    /**
     * @param Application $app
     * @param $user string
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function validateLogin(Application $app, $user)
    {
        $resultFalseMessage = 'Failed logging in. Reason: ';
        $resultTrueMessage = 'Successfully logged in.';

        switch ($user) {
            case 'author':
                $apiObject = new AuthorApi(new AuthorData($app));
                break;
            case 'commentator':
                $apiObject = new CommentatorApi(new CommentatorData($app));
                break;
            default:
                $app['session']->getFlashBag()->add('message', 'Unknown user login attempt.');
                return $this->index($app);
        }

        $apiObject->setPasswordObject(new Password());

        try {
            $result = $apiObject->login($_POST);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
        }

        if (!isset($result) || !$result) {
            $app['session']->getFlashBag()->add('message', $resultFalseMessage);
            if (isset($message)) {
                $app['session']->getFlashBag()->add('message', $message);
            }
//            return $app->redirect($failureRedirPath, $failureRedirCode);
            return $this->viewLogin($app, $user);
        }
        $app['session']->getFlashBag()->add('message', $resultTrueMessage);
        return $this->index($app);
    }

    /**
     * @param Application $app
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newPost(Application $app)
    {
        $apiObject = new PostApi(new PostData($app));

        try {
            $result = $apiObject->create($_POST);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
        }

        if (isset($message)) {
            $app['session']->getFlashBag()->add('message', $message);
        }

        if (!isset($result) || !$result) {
            $app['session']->getFlashBag()->add('message', 'Failed to add post.');
            return $this->index($app);
        }
        return $this->index($app);
    }

    /**
     * @param Application $app
     * @param $post_id int
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newComment(Application $app, $post_id)
    {
        $apiObject = new CommentApi(new CommentData($app));

        try {
            $result = $apiObject->create($post_id, $_POST);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
        }

        if (isset($message)) {
            $app['session']->getFlashBag()->add('message', $message);
        }

        if (!isset($result) || !$result) {
            $app['session']->getFlashBag()->add('message', 'Failed to add comment.');
            return $this->viewReadPost($app, $post_id);
        }
        $app['session']->getFlashBag()->add('message', 'Comment added.');
        return $this->viewReadPost($app, $post_id);
    }

    /**
     * @param Application $app
     * @param $post_id int
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editPost(Application $app, $post_id)
    {
        $apiObject = new PostApi(new PostData($app));

        try {
            $result = $apiObject->update($post_id, $_POST);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
        }

        if (isset($message)) {
            $app['session']->getFlashBag()->add('message', $message);
        }

        if (!isset($result) || !$result) {
            $app['session']->getFlashBag()->add('message', 'Failed to update post.');
            return $this->viewReadPost($app, $post_id);
        }
        $app['session']->getFlashBag()->add('message', 'Post edited.');
        return $this->viewReadPost($app, $post_id);
    }

    /**
     * @param Application $app
     * @param $post_id int
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removePost(Application $app, $post_id)
    {
        $apiObject = new PostApi(new PostData($app));
        $apiCommentObject = new CommentApi(new CommentData($app));

        try {
            $result = $apiObject->delete($post_id);
            $resultComment = $apiCommentObject->deleteAllForPost($post_id);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
        }

        if (isset($message)) {
            $app['session']->getFlashBag()->add('message', $message);
        }

        if (!isset($result) || !$result) {
            $app['session']->getFlashBag()->add('message', 'Failed to completely remove post.');
            return $this->index($app);
        }
        $app['session']->getFlashBag()->add('message', 'Deleted post.');
        return $this->index($app);
    }

    /**
     * @param Application $app
     * @param $post_id int
     * @param $comment_id int
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removeComment(Application $app, $post_id, $comment_id)
    {
        $apiObject = new CommentApi(new CommentData($app));

        try {
            $result = $apiObject->delete($comment_id);
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
        } catch (\UnexpectedValueException $e) {
            $message = $e->getMessage();
        }

        if (isset($message)) {
            $app['session']->getFlashBag()->add('message', $message);
        }

        if (!isset($result) || !$result) {
            $app['session']->getFlashBag()->add('message', 'Failed to remove comment.');
            return $this->viewReadPost($app, $post_id);
        }
        $app['session']->getFlashBag()->add('message', 'Deleted comment.');
        return $this->viewReadPost($app, $post_id);
    }
}
