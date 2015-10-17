<?php

namespace BasicBlog\Author;

/**
 * Class AuthorHydrator
 *
 * Hydrator for a complete Author object
 *
 * @package BasicBlog\Author
 */
class AuthorHydrator
{

    /**
     * @var array
     */
    protected $EXPECTED_AUTHORSHIP_RECORD = [
        'author_id',
        'email',
        'first_name',
        'last_name',
    ];

    /**
     * @param $object Author
     * @param $data array
     *
     * @returns Author
     */
    public function hydrate(Author $object, array $data)
    {
        $object->setBody($data['body']);
        $object->setPostId($data['post_id']);
        $object->setAuthorId($data['author_id']);
        $object->setTitle($data['title']);
        $object->setCreated($data['created']);
        $object->setUpdated($data['updated']);

//        $object->setAuthor() --> todo: object

        return $object;
    }
}
