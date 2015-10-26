<?php

namespace BasicBlog\Author;

use BasicBlog\Common\UserSessionInterface;
use BasicBlog\Common\DataAwareInterface;
use BasicBlog\Common\DataAwareTrait;
use BasicBlog\Security\PasswordAwareTrait;
use BasicBlog\Security\ValidationTrait;
use Silex\Application;

/**
 * Class AuthorApi
 *
 * Handle Author Objects
 *
 * @package BasicBlog\Author
 */
class AuthorApi implements DataAwareInterface, UserSessionInterface
{
    use ValidationTrait;
    use DataAwareTrait;
    use PasswordAwareTrait;

    /**
     * @param $data
     *
     * @return bool|mixed
     */
    public function create($data)
    {
        $dataObject = $this->getDataObject();
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
            throw new \InvalidArgumentException('Password fields did not match.', 1);
        }

        // Password Hashing
        $passwordObject = $this->getPasswordObject();
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
     * @param $data array
     *
     * @return bool|mixed
     */
    public function login(array $data)
    {
        // Filtering Raw Data
        $formFieldFilters = [
            'email_address' => FILTER_VALIDATE_EMAIL,
            'password' => FILTER_SANITIZE_STRING,
        ];
        $validData = $this->checkDataIntegrity($data, $formFieldFilters);

        // Get records
        $dataObject = $this->getDataObject();
        $record = $dataObject->fetchAuthorDataByEmail($validData['email_address']);

        // Password Hashing
        $passwordObject = $this->getPasswordObject();
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
        $dataObject->getSession()->set('author', [
            'email_address' => $record['email'],
            'author_id' => $record['author_id'],
            'first_name' => $record['first_name'],
            'last_name' => $record['last_name'],
        ]);
        //todo: session timeout

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function logout()
    {
        $dataObject = $this->getDataObject();
        if ($dataObject->getSession()->remove('author')) {
            return true;
        }
        return false;
    }

    /**
     * Fetch author data, no password
     *
     * @param $id integer
     *
     * @return array
     */
    public function fetchBasics($id)
    {
        $dataObject = $this->getDataObject();
        $data = $dataObject->fetchAuthorBasicDataById($id);

        return $data;
    }

    /**
     * Fetch author data
     *
     * @param $id integer
     *
     * @return array
     */
    public function fetchFull($id)
    {
        $dataObject = $this->getDataObject();
        $data = $dataObject->fetchAuthorDataById($id);

        return $data;
    }
}
