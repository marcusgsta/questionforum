<?php

namespace Anax\User\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Anax\User\User;

/**
 * Example of FormModel implementation.
 */
class CreateUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di)
    {
        parent::__construct($di);

        $editable = "hidden";
        $acronym = $this->di->session->get("user");

        if (isset($acronym)) {
            $role = $this->di->get("commentController")->getRole($acronym);
            if ($role == 10) {
                $editable = "text";
            }
        }

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Create user",
            ],
            [
                "acronym" => [
                    "type"        => "text",
                ],

                "password" => [
                    "type"        => "password",
                ],

                "password-again" => [
                    "type"        => "password",
                    "validation" => [
                        "match" => "password"
                    ],
                ],
                "email" => [
                    "type"        => "text",
                ],
                "role" => [
                    "type"        => $editable,
                    "value"       => 1,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Create user",
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

        $acronym       = $this->form->value("acronym");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");
        $email         = $this->form->value("email");
        $role          = $this->form->value("role");

        // Check password matches
        if ($password !== $passwordAgain) {
            $this->form->rememberValues();
            $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
            $this->form->addOutput(
                "<div class='alert alert-success alert-dismissable'>"
                 . $link .
                 "Lösenord matchade inte.</div>"
            );
            return false;
        }

        // Save to database
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->acronym = $acronym;
        $user->email = $email;
        $user->role = $role;
        // create gravatar from email
        $gravatar = $user->gravatar($email);
        $user->gravatar = $gravatar;

        $user->setPassword($password);
        $user->save();
        $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
        $this->form->addOutput(
            "<div class='alert alert-success alert-dismissable'>"
             . $link .
             "Användaren skapades!</div>"
        );

        return true;
    }
}
