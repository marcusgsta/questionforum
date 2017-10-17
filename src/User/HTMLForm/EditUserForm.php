<?php

namespace Anax\User\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Anax\User\User;

/**
 * Example of FormModel implementation.
 */
class EditUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $acronym)
    {
        parent::__construct($di);

        $user = new User();
        $user->setDb($this->di->get("db"));

        // $acronym = $this->di->session->get("user");
        $user = $user->find("acronym", $acronym);

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Redigera användare",
            ],
            [
                "id" => [
                    "value"       => $user->id,
                    "type"        => "hidden",
                ],
                "acronym" => [
                    "type"        => "text",
                    "value"       => $user->acronym,
                ],

                "password" => [
                    "type"        => "password",
                ],
                "role" => [
                    "type"        => "hidden",
                    "value"       => 1,
                ],

                "password-again" => [
                    "type"        => "password",
                    "validation" => [
                        "match" => "password"
                    ],
                ],
                "email" => [
                    "type"        => "text",
                    "value"       => $user->email,
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
        $id            = $this->form->value("id");
        $acronym       = $this->form->value("acronym");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");
        $email         = $this->form->value("email");

        // Check password matches
        if ($password !== $passwordAgain) {
            $this->form->rememberValues();
            $this->form->addOutput("Password did not match.");
            return false;
        }

        // Save to database
        // $db = $this->di->get("db");
        // $password = password_hash($password, PASSWORD_DEFAULT);
        // $db->connect()
        //    ->insert("User", ["acronym", "password"])
        //    ->execute([$acronym, $password]);
        $user = new User();
        $user->setDb($this->di->get("db"));

        $user->acronym = $acronym;
        $user->email = $email;
        $user->id = $id;
        $user->role = 1;

        // create/update gravatar from email
        $gravatar = $user->gravatar($email);
        $user->gravatar = $gravatar;

        $user->setPassword($password);
        $user->save();

        $this->form->addOutput("Användarprofil uppdaterades.");
        return true;
    }
}
