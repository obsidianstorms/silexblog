<?php

namespace BasicBlog\Commentator;

use BasicBlog\Security\Password;
use BasicBlog\Security\ValidationTrait;
use Silex\Application;

/**
 * Class CommentatorFactory
 *
 * Handle Commentator Objects
 *
 * @package BasicBlog\Commentator
 */
class CommentatorFactory
{
    use ValidationTrait;

    /**
     * @param $app Application
     * @param $data
     *
     * @return bool|mixed
     */
    public function create(Application $app, $data)
    {
        // Filtering Raw Data
        $formFieldFilters = [
            'username' => FILTER_SANITIZE_STRING,
            'password' => FILTER_SANITIZE_STRING,
            'password_confirm' => FILTER_SANITIZE_STRING,
        ];
        $validData = $this->checkDataIntegrity($data, $formFieldFilters);

        // Password matching
        if ($validData['password'] != $validData['password_confirm']) {
            throw new \InvalidArgumentException('Password fields did not match.', 2);
        }

        $dataObject = new CommentatorData($app);
        // Commentator Data Object
        // Check if an username already exists, exit if one does
        if ($dataObject->doesUsernameExist($validData['username'])) {
            return false;
        }

        // Password Hashing
        $passwordObject = new Password();
        $validData['password_hash'] = $passwordObject->createHashedPassword($validData['password'])->getHash();

        $dataToInsert = [
            'username' => $validData['username'],
            'password_hash' => $validData['password_hash'],
        ];

        // Save data to database
        $id = $dataObject->create($dataToInsert);

        return $id;
    }

    /**
     * @param $app Application
     * @param $data
     *
     * @return bool|mixed
     */
    public function login(Application $app, $data)
    {
        // Filtering Raw Data
        $formFieldFilters = [
            'username' => FILTER_SANITIZE_STRING,
            'password' => FILTER_SANITIZE_STRING,
        ];
        $validData = $this->checkDataIntegrity($data, $formFieldFilters);

        // Get records
        $dataObject = new CommentatorData($app);
        $record = $dataObject->fetchCommentatorByUsername($validData['username']);

        // Password Hashing
        $passwordObject = new Password();
        $isValidPassword = $passwordObject->verifyPassword($validData['password'], $record['password_hash']);

        if (!$isValidPassword) {
            return false;
        }

        // Update password hash if necessary
        if (!$passwordObject->isSecurePassword()) {
            $newHash = $passwordObject->getHash();
            $dataObject->updatePassword($record['commentator_id'], $newHash);
        }

        // Set Session
        $app['session']->set('commentator', [
            'username' => $record['username'],
            'commentator_id' => $record['commentator_id'],
        ]);
        //todo: session timeout

        return true;
    }

    /**
     * @param $app Application
     *
     * @return bool|mixed
     */
    public function logout(Application $app)
    {
        $app['session']->remove('commentator');
        return true;
    }

    /**
     * Fetch commentator data, not password
     *
     * @param $app Application
     * @param $id integer
     *
     * @return array
     */
    public function fetchBasics(Application $app, $id)
    {
        $dataObject = new CommentatorData($app);
        $data = $dataObject->fetchCommentatorById($id);

        return $data;
    }

    /**
     * Fetch full commentator data
     *
     * @param $app Application
     * @param $id integer
     *
     * @return array
     */
    public function fetchFull(Application $app, $id)
    {
        $dataObject = new CommentatorData($app);
        $data = $dataObject->fetchCommentatorFullDataById($id);

        return $data;
    }


}
