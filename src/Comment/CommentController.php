<?php

namespace Marcusgsta\Comment;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Anax\User\User;
use \Anax\TextFilter\TextFilter;
use \Marcusgsta\HTMLForm\CreateCommentForm;
use \Marcusgsta\Vote\VoteComment;

/**
 * A class for everything to do with comments.
 */
class CommentController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    /**
    * Get comment
    * @param integer commentid
    * @param object comment
    */
    public function getComment($commentid)
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("db"));
        $commentObject = $comment->find("id", $commentid);
        return $commentObject;
    }


    /**
    * Get comments
    */
    public function getMyComments()
    {
        $comments = new Comment();
        $comments->setDb($this->di->get("db"));
        $orderby = "id ASC";
        $commentObjects = $comments->findAll($orderby);
        return $commentObjects;
    }

    /**
    * escape and filter comments
    * @param array of comment objects
    * @return array of filtered comment objects
    */
    public function escapeAndFilterComments($commentObjects)
    {
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
    * Get comments
    */
    public function getComments()
    {
        $comments = new Comment();
        $comments->setDb($this->di->get("db"));
        $orderby = "id ASC";
        $commentObjects = $comments->findAll($orderby);
        // escape output
        $commentObjects = $this->escapeAndFilterComments($commentObjects);

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
        $commentObjects = $this->escapeAndFilterComments($commentObjects);
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


    /**
    * vote a comment up or down
    * @param integer commentid
    * @param integer score
    * @return void
    **/
    public function vote($commentid, $score)
    {
        // Get userid
        $user = $this->di->get("userController")->getLoggedinUser();
        $userid = $user->id;

        $comment = $this->getComment($commentid);
        // $comment->setDb($this->di->get("db"));

        // $comment->find("id", $commentid);
        // update
        //$sql = "UPDATE comment SET votesum = votesum + $score";
        if ($score == 1) {
            $comment->votesum = $comment->votesum + 1;
        } elseif ($score == 0) {
            $comment->votesum = $comment->votesum - 1;
        }
        $comment->save();

        // update table Votecomment to track vote
        $votecomment = new VoteComment();
        $votecomment->setDb($this->di->get("db"));
        $votecomment->userid = $userid;
        $votecomment->commentid = $commentid;
        $votecomment->save();
        // redirect to previous page
        $url = $this->di->get("request")->getServer('HTTP_REFERER');
        $url = $url . "#comment-" . $commentid;
        $this->di->get("response")->redirect($url);
        return true;

        // redirect to previous page
        $url = $this->di->get("request")->getServer('HTTP_REFERER');
        $url = $url . "#comment-" . $commentid;
        $this->di->get("response")->redirect($url);
        return true;
    }

    /**
    * Check if user has voted for comment
    *
    **/
    public function userHasVoted($commentid)
    {
        $user = $this->di->get("userController")->getLoggedinUser();
        $userid = $user->id;

        $votecomment = new VoteComment();
        $votecomment->setDb($this->di->get("db"));

        $where = "userid = ? AND commentid = ?";
        $value = [$userid, $commentid];
        $hasVoted = $votecomment->findWhere($where, $value);

        return $hasVoted;
    }
}
