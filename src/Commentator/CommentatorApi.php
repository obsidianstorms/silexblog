<?php

namespace BasicBlog\Commentator;

use BasicBlog\Common\UserSessionInterface;
use BasicBlog\Common\DataAwareInterface;
use BasicBlog\Common\DataAwareTrait;
use BasicBlog\Security\PasswordAwareTrait;
use BasicBlog\Security\ValidationTrait;
use Silex\Application;

/**
 * Class CommentatorApi
 *
 * Handle Commentator Objects
 *
 * @package BasicBlog\Commentator
 */
class CommentatorApi implements DataAwareInterface, UserSessionInterface
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
        // Filtering Raw Data
        $formFieldFilters = [
            'username' => FILTER_SANITIZE_STRING,
            'password' => FILTER_SANITIZE_STRING,
            'password_confirm' => FILTER_SANITIZE_STRING,
        ];
        $validData = $this->checkDataIntegrity($data, $formFieldFilters);

        // Password matching
        if ($validData['password'] != $validData['password_confirm']) {
            throw new \InvalidArgumentException('Password fields did not match.', 1);
        }

        $dataObject = $this->getDataObject();
        // Commentator Data Object
        // Check if an username already exists, exit if one does
        if ($dataObject->doesUsernameExist($validData['username'])) {
            return false;
        }

        // Password Hashing
        $passwordObject = $this->getPasswordObject();
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
     * @param $data
     *
     * @return bool|mixed
     */
    public function login(array $data)
    {
        // Filtering Raw Data
        $formFieldFilters = [
            'username' => FILTER_SANITIZE_STRING,
            'password' => FILTER_SANITIZE_STRING,
        ];
        $validData = $this->checkDataIntegrity($data, $formFieldFilters);

        // Get records
        $dataObject = $this->getDataObject();
        $record = $dataObject->fetchCommentatorByUsername($validData['username']);

        // Password Hashing
        $passwordObject = $this->getPasswordObject();
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
        $dataObject->getSession()->set('commentator', [
            'username' => $record['username'],
            'commentator_id' => $record['commentator_id'],
        ]);
        //todo: session timeout

        return true;
    }

    /**
     * @return bool
     */
    public function logout()
    {
        $dataObject = $this->getDataObject();
        if ($dataObject->getSession()->remove('commentator')) {
            return true;
        }
        return false;
    }

    /**
     * Fetch commentator data, not password
     *
     * @param $id integer
     *
     * @return array
     */
    public function fetchBasics($id)
    {
        $dataObject = $this->getDataObject();
        $data = $dataObject->fetchCommentatorBasicDataById($id);

        return $data;
    }

    /**
     * Fetch full commentator data
     *
     * @param $id integer
     *
     * @return array
     */
    public function fetchFull($id)
    {
        $dataObject = $this->getDataObject();
        $data = $dataObject->fetchCommentatorFullDataById($id);

        return $data;
    }
}
