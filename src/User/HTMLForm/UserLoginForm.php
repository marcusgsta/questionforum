<?php

namespace Anax\User\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Anax\User\User;

/**
 * Example of FormModel implementation.
 */
class UserLoginForm extends FormModel
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
                "legend" => "Logga in"
            ],
            [
                "user" => [
                    "type"        => "text",
                    //"description" => "Here you can place a description.",
                    //"placeholder" => "Here is a placeholder",
                ],

                "password" => [
                    "type"        => "password",
                    //"description" => "Here you can place a description.",
                    //"placeholder" => "Here is a placeholder",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Login",
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
        $acronym       = $this->form->value("user");
        $password      = $this->form->value("password");

        // Try to login
        // $db = $this->di->get("db");
        // $db->connect();
        // $user = $db->select("password")
        //            ->from("User")
        //            ->where("acronym = ?")
        //            ->executeFetch([$acronym]);
        //
        // // $user is false if user is not found
        // if (!$user || !password_verify($password, $user->password)) {
        //    $this->form->rememberValues();
        //    $this->form->addOutput("User or password did not match.");
        //    return false;
        // }

        $user = new User();
        $user->setDb($this->di->get("db"));
        $res = $user->verifyPassword($acronym, $password);

        if (!$res) {
            $this->form->rememberValues();

            $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
            $this->form->addOutput(
                "<div class='alert alert-danger alert-dismissable'>"
                 . $link .
                 "Användare eller lösenord matchade inte</div>"
            );

            return false;
        }

        $this->di->get("loginController")->loginUser($user->acronym);

        // $this->form->addOutput("Användare " . $user->acronym . " loggade in.");

        $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
        $this->form->addOutput(
            "<div class='alert alert-success alert-dismissable'>"
             . $link .
             "Användare " . $user->acronym . " loggade in</div>"
        );

        return true;
    }
}
