<?php

namespace Marcusgsta\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Anax\User\UserController;
use \Marcusgsta\Question\Question;
use \Marcusgsta\Answer\Answer;
use \Marcusgsta\Comment\Comment;

/**
 * Example of FormModel implementation.
 */
class CreateCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $questionid, $answerid)
    {
        parent::__construct($di);
        $user = new UserController;
        $user->setDI($this->di);
        $user = $user->getLoggedinUser();
        $userid = $user->id;

        // questionid should be null for comments-answer
        // answerid should be null for comments-question

        // Set class for different kinds of forms
        $class = isset($questionid) ? "comment-question" : "comment-answer-" . $answerid;

        $this->form->create(
            [
                "id" => __CLASS__ . $class,
                "legend" => "Lägg till kommentar",
                "class" => $class
            ],
            [
                "commenttext" => [
                    "type"        => "textarea",
                    "name"        => "commenttext"
                ],
                "userid" => [
                    "type"        => "hidden",
                    "value"       => $userid,
                ],
                "questionid" => [
                    "type"        => "hidden",
                    "value"       => $questionid,
                ],
                "answerid" => [
                    "type"        => "hidden",
                    "value"       => $answerid,
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Submit",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        $commenttext            = $this->form->value("commenttext");
        $userid                 = $this->form->value("userid");
        $questionid             = $this->form->value("questionid");
        $answerid               = $this->form->value("answerid");

        // Save to database

        $comment = new Comment();
        $comment->setDb($this->di->get("db"));

        if (!isset($commenttext)) {
            $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
            $this->form->addOutput(
                "<div class='alert alert-danger alert-dismissable'>
                . $link . Försök igen. Skrev du ingen kommentar?</div>"
            );
            return false;
        }

        $comment->commenttext = $commenttext;
        $comment->userid = $userid;
        $comment->questionid = $questionid;
        $comment->answerid = $answerid;

        $createdDate = date("G:i:s M jS Y", time());
        $comment->created = $createdDate;

        $comment->save();
        $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
        $this->form->addOutput(
            "<div class='alert alert-success alert-dismissable'>
            . $link . Tack för din kommentar!</div>"
        );
        return true;
    }
}
