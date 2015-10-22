<?php

namespace BasicBlog\Author;

use BasicBlog\Security\Password;
use BasicBlog\Security\ValidationTrait;
use BasicBlog\Common\UserSessionInterface;
use Silex\Application;

/**
 * Class AuthorApi
 *
 * Handle Author Objects
 *
 * @package BasicBlog\Author
 */
class AuthorApi implements UserSessionInterface
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
        $dataObject = new AuthorData($app);
        // Author Data Object
        // Check if an author already exists, exit if one does
        if ($dataObject->doAuthorsExist()) {
            return false;
        }

        // Filtering Raw Data
        $formFieldFilters = [
            'email_address' => FILTER_VALIDATE_EMAIL,
            'password' => FILTER_SANITIZE_STRING,
            'password_confirm' => FILTER_SANITIZE_STRING,
            'first_name' => FILTER_SANITIZE_STRING,
            'last_name' => FILTER_SANITIZE_STRING,
        ];
        $validData = $this->checkDataIntegrity($data, $formFieldFilters);

        // Password matching
        if ($validData['password'] != $validData['password_confirm']) {
            throw new \InvalidArgumentException('Password fields did not match.', 2);
        }

        // Password Hashing
        $passwordObject = new Password();
        $validData['password_hash'] = $passwordObject->createHashedPassword($validData['password'])->getHash();

        $dataToInsert = [
            'email' => $validData['email_address'],
            'password_hash' => $validData['password_hash'],
            'first_name' => $validData['first_name'],
            'last_name' => $validData['last_name'],
        ];

        // Save data to database
        $id = $dataObject->createNewAuthor($dataToInsert);

        return $id;
    }

    /**
     * @param $app Application
     * @param $data array
     *
     * @return bool|mixed
     */
    public function login(Application $app, array $data)
    {
        // Filtering Raw Data
        $formFieldFilters = [
            'email_address' => FILTER_VALIDATE_EMAIL,
            'password' => FILTER_SANITIZE_STRING,
        ];
        $validData = $this->checkDataIntegrity($data, $formFieldFilters);

        // Get records
        $dataObject = new AuthorData($app);
        $record = $dataObject->fetchAuthorDataByEmail($validData['email_address']);

        // Password Hashing
        $passwordObject = new Password();
        $isValidPassword = $passwordObject->verifyPassword($validData['password'], $record['password_hash']);

        if (!$isValidPassword) {
            return false;
        }

        // Update password hash if necessary
        if (!$passwordObject->isSecurePassword()) {
            $newHash = $passwordObject->getHash();
            $dataObject->updatePassword($record['author_id'], $newHash);
        }

        // Set Session
        $app['session']->set('author', [
            'email_address' => $record['email'],
            'author_id' => $record['author_id'],
            'first_name' => $record['first_name'],
            'last_name' => $record['last_name'],
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
        $app['session']->remove('author');
        return true;
    }

    /**
     * Fetch author data, no password
     *
     * @param $app Application
     * @param $id integer
     *
     * @return array
     */
    public function fetchBasics(Application $app, $id)
    {
        $dataObject = new AuthorData($app);
        $data = $dataObject->fetchAuthorBasicDataById($id);

        return $data;
    }

    /**
     * Fetch author data
     *
     * @param $app \Silex\Application
     * @param $id integer
     *
     * @return array
     */
    public function fetchFull(Application $app, $id)
    {
        $dataObject = new AuthorData($app);
        $data = $dataObject->fetchAuthorDataById($id);

        return $data;
    }
}
