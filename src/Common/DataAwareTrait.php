<?php

namespace BasicBlog\Common;

/**
 * interface DataAwareTrait
 *
 * @package BasicBlog\Common
 */
trait DataAwareTrait
{
    /**
     * @var object
     */
    protected $dataObject;

    /**
     * {@inheritDoc}
     */
    public function __construct($dataObject)
    {
        $this->dataObject = $dataObject;
    }

    /**
     * {@inheritDoc}
     */
    public function getDataObject()
    {
        return $this->dataObject;
    }

    /**
     * {@inheritDoc}
     */
    public function setDataObject($dataObject)
    {
        $this->dataObject = $dataObject;
        return $this;
    }
}
