<?php

namespace Marcusgsta\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Marcusgsta\Comment\Comment;

/**
 * Form to delete an item.
 */
class DeleteCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Delete an item",
            ],
            [
                "select" => [
                    "type"        => "select",
                    "label"       => "Select item to delete:",
                    "options"     => $this->getAllItems(),
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Delete item",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Get all items as array suitable for display in select option dropdown.
     *
     * @return array with key value of all items.
     */
    protected function getAllItems()
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("db"));
        $allComments = $comment->findAll();

        // filter array of comments to current user
        $newArray = array_filter($allComments, function ($obj) {
            $acronym = $this->di->session->get("user");
            $role = $this->di->get("commentController")->getRole($acronym);
            // if user is not administrator ...
            if ($role != 10) {
                // filter out user's comment
                if ($obj->acronym != $acronym) {
                    return false;
                }
                return true;
            }
            return true;
        });

        $comments = ["-1" => "Select an item..."];
        foreach ($newArray as $obj) {
            $comments[$obj->id] = "{$obj->commenttext} ({$obj->id})";
        }

        return $comments;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("db"));
        $comment->find("id", $this->form->value("select"));
        $comment->delete();

        $this->form->addOutput("Kommentaren raderades.");
        $this->di->get("response")->redirect("comment/delete");
    }
}
