<?php

namespace BasicBlog\Author;

use Silex\Application;

class Register
{
    public function createAuthor(Application $app)
    {
        $authorData = new AuthorData($app);
//        try {
//            $authorExists = $authorData->doAuthorsExist();
//        } catch () {
//
//        }

    }
}