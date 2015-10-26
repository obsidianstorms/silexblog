<?php

namespace BasicBlog\Common;

/**
 * interface DataAwareInterface
 *
 * @package BasicBlog\Common
 */
interface DataAwareInterface
{
    /**
     * @param $dataObject
     */
    public function __construct($dataObject);

    /**
     * @return object
     */
    public function getDataObject();

    /**
     * @param $dataObject object
     *
     * @return static
     */
    public function setDataObject($dataObject);
}
