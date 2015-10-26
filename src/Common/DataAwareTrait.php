<?php

namespace BasicBlog\Common;

/**
 * trait DataAwareTrait
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
        //todo: typehinting likely with dataObject refactor
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
