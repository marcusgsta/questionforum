<?php

namespace Anax\User\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Anax\User\User;

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
        $allUsers = $user->findAll();

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
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("id", $this->form->value("select"));
        $user->delete();

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
