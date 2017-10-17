<?php

namespace Marcusgsta\Comment;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Anax\User\User;
use \Anax\TextFilter\TextFilter;
use \Marcusgsta\HTMLForm\CreateCommentForm;

/**
 * A class for everything to do with comments.
 */
class CommentController implements InjectionAwareInterface
{
    use InjectionAwareTrait;


    /**
    * Get comments
    */
    public function getComments()
    {
        $comments = new Comment();
        $comments->setDb($this->di->get("db"));
        $orderby = "id ASC";
        $commentObjects = $comments->findAll($orderby);
        // escape output
        $newArray = array_filter($commentObjects, function ($obj) {
            $obj->commenttext = htmlspecialchars($obj->commenttext);
            // filter output with filters
            $textfilter = new TextFilter;
            $obj->commenttext = $textfilter->parse($obj->commenttext, ["markdown"]);
            return true;
        });
        $commentObjects = $newArray;

        return $commentObjects;
    }

    /**
    * Get comments for one answer
    * Escape and filter commenttext-column
    * @param integer answerid
    * @return array commentObjects
    */
    public function getAnswerComments($answerid)
    {
        $comments = new Comment();
        $comments->setDb($this->di->get("db"));
        $where = "answerid = ?";
        $value = $answerid;
        $commentObjects = $comments->findAllWhere($where, $value);
        // escape output
        $newArray = array_filter($commentObjects, function ($obj) {
            $obj->commenttext = htmlspecialchars($obj->commenttext);
            // filter output with filters
            $textfilter = new TextFilter;
            $obj->commenttext = $textfilter->parse($obj->commenttext, ["markdown"]);
            return true;
        });
        $commentObjects = $newArray;

        return $commentObjects;
    }


    /**
    * get a user's role
    * @param string user's acronym
    *
    * @return integer user's role
    **/
    public function getRole($acronym)
    {
        $user = new \Anax\User\User();
        $user->setDb($this->di->get("db"));
        $user->find("acronym", $acronym);
        $role = isset($user->role) ? $user->role : null;
        return $role;
    }
}
