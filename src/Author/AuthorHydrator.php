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
    protected static $EXPECTED_AUTHORSHIP_RECORD = [
        'author_id',
        'email',
        'first_name',
        'last_name',
    ];

    /**
     * @var array
     */
    protected static $EXPECTED_FULL_RECORD = [
        'author_id',
        'email',
        'first_name',
        'last_name',
        'password_hash',
    ];

    /**
     * @var array
     */
    protected static $EXPECTED_CREATE_FORM_FILTER = [
        'email' => FILTER_VALIDATE_EMAIL,
        'first_name' => FILTER_SANITIZE_STRING,
        'last_name' => FILTER_SANITIZE_STRING,
        'password' => FILTER_SANITIZE_STRING,
        'password_confirm' => FILTER_SANITIZE_STRING,
    ];

    /**
     * @return array
     */
    public static function getAuthorshipMask()
    {
        return static::$EXPECTED_AUTHORSHIP_RECORD;
    }

    /**
     * @return array
     */
    public static function getFullMask()
    {
        return static::$EXPECTED_FULL_RECORD;
    }

    /**
     * @return array
     */
    public static function getCreateMask()
    {
        return static::$EXPECTED_CREATE_FORM_FILTER;
    }

    /**
     * @param $object Author
     * @param $data array
     * @param $mask array
     *
     * @returns Author
     */
    public function hydrate(Author $object, array $data, array $mask)
    {
        foreach ($mask as $field) {
            $fieldNames = explode('_', $field);
            $operationResult = array_walk($fieldNames, function ($value, $key) {
                $value = ucfirst($value);
            });
            if (!$operationResult) {
                throw new \RuntimeException("Unexpected failure in processing.");
            }
            $fieldName = implode('', $fieldNames);
        }

        $method = 'set' . $fieldName;
        $object->$method($data[$field]);

        return $object;
    }
}
