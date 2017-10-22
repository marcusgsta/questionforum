<?php

namespace Anax\User\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Anax\User\User;
use \Marcusgsta\Vote\VoteQuestion;
use \Marcusgsta\Vote\VoteAnswer;
use \Marcusgsta\Vote\VoteComment;

/**
 * Form to delete an item.
 */
class DeleteUserForm extends FormModel
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
                "legend" => "Radera användare",
            ],
            [
                "select" => [
                    "type"        => "select",
                    "label"       => "Välj användare att radera:",
                    "options"     => $this->getAllItems(),
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Ta bort användare",
                    "callback" => [$this, "callbackSubmit"]
                ],
                "submit2" => [
                    "type" => "submit",
                    "value" => "Redigera användare",
                    "callback" => [$this, "callbackSubmit2"]
                ],
            ]
        );
    }



    /**
     * Get all items as array suitable for display in select option dropdown.
     *
     * @return array with key value of all items.
     */
    // protected function getAllItems()
    public function getAllItems()
    {
        $user = new User();
        $user->setDb($this->di->get("db"));
        $orderby = "id ASC";
        $allUsers = $user->findAll($orderby);

        $users = ["-1" => "Select an item..."];
        foreach ($allUsers as $obj) {
            $users[$obj->id] = "{$obj->acronym} ({$obj->id})";
        }

        return $users;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        $userid = $this->form->value("select");

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("id", $userid);

        // set user account to deleted
        $user->deleted = date("G:i:s M jS Y", time());
        $user->acronym = "Raderad användare";
        $user->email = "no@nomail.com";
        $user->rank = 0;
        $user->gravatar = $user->gravatar($user->email);
        $user->password = "XYXYXY";
        $user->role = 1;
        $user->save();
        // $user->delete();

        // erase connections for voting

        $voteQuestion = new VoteQuestion();
        $voteQuestion->setDb($this->di->get("db"));
        $voteQuestion->deleteWhere("userid = ?", $userid);

        $voteAnswer = new VoteAnswer();
        $voteAnswer->setDb($this->di->get("db"));
        $voteAnswer->deleteWhere("userid = ?", $userid);

        $voteComment = new VoteComment();
        $voteComment->setDb($this->di->get("db"));
        $voteComment->deleteWhere("userid = ?", $userid);

        $this->form->addOutput("Användaren raderades.");
        $this->di->get("response")->redirect("user/edit-all");
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit2()
    {
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("id", $this->form->value("select"));

        $id = $this->form->value("select");

        $this->di->get("response")->redirect("user/edit/$id");
    }
}
