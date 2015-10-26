<?php

namespace BasicBlog\Commentator;

use Silex\Application;
use BasicBlog\Common\ApplicationAwareInterface;
use BasicBlog\Common\ApplicationAwareTrait;
/**
 * Class CommentatorFactory
 *
 * @package BasicBlog\Commentator
 */
class CommentatorFactory implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @return CommentatorData
     */
    public function getNewCommentator()
    {
        return new CommentatorData($this->app);
    }
}
