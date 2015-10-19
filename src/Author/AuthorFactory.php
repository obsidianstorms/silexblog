<?php

namespace BasicBlog\Author;


/**
 * Class PostFactory
 *
 * Handle Post Objects
 *
 * @package BasicBlog\Post
 */
class AuthorFactory
{

    public function create($app, $data)
    {
        // Author Data Object
        // Check if an author already exists, exit if one does
        $dataObject = new AuthorData($app);
        if (!$dataObject->doAuthorsExist()) {
            return false;
        }

        // Validation
        $filteredData = [];
        $mask = AuthorHydrator::getCreateMask();
        foreach ($mask as $key => $filter) {
            $filteredData[$key] = filter_var($data[$key], $filter);
        }
        if (in_array(false, $filteredData, true)) {
            $key = array_search(false, $filteredData, true);
            throw new \InvalidArgumentException(sprintf('Invalid data submitted: %s.', $key), 2);
        }

        // Password matching
        if ($filteredData['password'] != $filteredData['passwordConfirm']) {
            throw new \InvalidArgumentException('Password fields did not match.', 3);
        }

        // Password Hashing
        $passwordObject = new Password();
        $filteredData['password_hash'] = $passwordObject->createHashedPassword($filteredData['password'])->getHash();

        // Author Data Object
        // Prep filtered array for injection into database
        unset($filteredData['password']);
        unset($filteredData['password_confirm']);

        // Save data to database
        $id = $dataObject->createNewAuthor($filteredData);

        return $id;
    }

    /**
     * Fetch a hydrated post object
     *
     * @param $app \Silex\Application
     * @param $id integer
     *
     * @return Author
     */
    public function fetchBasics($app, $id)
    {
        $dataObject = new AuthorData($app);
        $data = $dataObject->fetchAuthorBasicDataById($id);

        $authorHydrator = new AuthorHydrator();
        try {
            $author = $authorHydrator->hydrate(new Author(), $data, AuthorHydrator::getAuthorshipMask());
        } catch (\InvalidArgumentException $e) {
            throw new \UnexpectedValueException('Attempted to process bad data.', 0);
        }
        return $author;
    }

    /**
     * Fetch a hydrated post object
     *
     * @param $app \Silex\Application
     * @param $id integer
     *
     * @return Author
     */
    public function fetchFull($app, $id)
    {
        $dataObject = new AuthorData($app);
        $data = $dataObject->fetchAuthorFullDataById($id);

        $authorHydrator = new AuthorHydrator();
        $authorHydrator->setApp($app);
        try {
            $post = $authorHydrator->hydrate(new Author(), $data, AuthorHydrator::getFullMask());
        } catch (\InvalidArgumentException $e) {
            throw new \UnexpectedValueException('Attempted to process bad data.', 1);
        }

        return $post;
    }


}
