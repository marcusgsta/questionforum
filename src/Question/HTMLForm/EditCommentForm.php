<?php

namespace Marcusgsta\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Marcusgsta\Comment\Comment;

/**
 * Example of FormModel implementation.
 */
class EditCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $data)
    {
        parent::__construct($di);

        $comment = new Comment();
        $comment->setDb($this->di->get("db"));
        $page = $data['page'];
        $id = $data['commentid'];
        $thisComment = $comment->find('id', $id);

        $commenttext = $thisComment->commenttext;
        $acronym = $thisComment->acronym;

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Redigera kommentar",
            ],
            [
                "id" => [
                    "value"       => $id,
                    "type"        => "hidden",
                ],
                "commenttext" => [
                    "type"        => "text",
                    "value"       => $commenttext,
                ],
                "acronym" => [
                    "type"        => "hidden",
                    "value"       => $acronym,
                ],
                "page" => [
                    "type"        => "hidden",
                    "value"       => $page,
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Skicka",
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
        $id             = $this->form->value("id");
        $commenttext    = $this->form->value("commenttext");
        $acronym        = $this->form->value("acronym");
        $page           = $this->form->value("page");

        // Save to database
        // $db = $this->di->get("db");
        // $password = password_hash($password, PASSWORD_DEFAULT);
        // $db->connect()
        //    ->insert("User", ["acronym", "password"])
        //    ->execute([$acronym, $password]);
        $comment = new Comment();
        $comment->setDb($this->di->get("db"));

        $comment->id = $id;
        $comment->commenttext = $commenttext;
        $comment->acronym = $acronym;
        $comment->page = $page;

        $comment->save();

        $this->form->addOutput("Kommentar uppdaterades.");
        return true;
    }
}
