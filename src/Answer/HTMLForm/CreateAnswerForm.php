<?php

namespace Marcusgsta\Answer\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Anax\User\UserController;
use \Marcusgsta\Answer\Answer;

/**
 * Example of FormModel implementation.
 */
class CreateAnswerForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $questionid)
    {
        parent::__construct($di);
        $user = new UserController;
        $user->setDI($this->di);
        $user = $user->getLoggedinUser();
        $userid = $user->id;

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Svara på en fråga",
                "class" => "answerquestion"
            ],
            [
                "answertitle" => [
                    "type"        => "text",
                    "name"        => "Svarstitel"
                ],
                "answertext" => [
                    "type"        => "textarea",
                    "name"        => "Svarstext"
                ],
                "userid" => [
                    "type"        => "hidden",
                    "value"       => $userid,
                ],
                "questionid" => [
                    "type"        => "hidden",
                    "value"       => $questionid,
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
        $answertitle           = $this->form->value("answertitle");
        $answertext            = $this->form->value("answertext");
        $userid                = $this->form->value("userid");
        $questionid            = $this->form->value("questionid");

        // Save to database

        $answer = new Answer();
        $answer->setDb($this->di->get("db"));

        if (!isset($answertitle, $answertext)) {
            $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
            $this->form->addOutput(
                "<div class='alert alert-danger alert-dismissable'>"
                . $link . "Försök igen. Skrev du både titel och text?</div>"
            );
            return false;
        }

        $answer->answertitle = $answertitle;
        $answer->answertext = $answertext;
        $answer->userid = $userid;
        $answer->questionid = $questionid;

        $createdDate = date("G:i:s M jS Y", time());
        $answer->created = $createdDate;

        $answer->save();
        $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
        $this->form->addOutput(
            "<div class='alert alert-success alert-dismissable'>" . $link . "Tack för ditt svar!</div>"
        );
        return true;
    }
}
