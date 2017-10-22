<?php

namespace Marcusgsta\Question\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Marcusgsta\Question\Question;

/**
 * Example of FormModel implementation.
 */
class EditQuestionForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $questionid)
    {
        parent::__construct($di);

        $question = new Question();
        $question->setDb($this->di->get("db"));

        $thisQuestion = $question->find('id', $questionid);
        $id = $thisQuestion->id;

        $questiontext = $thisQuestion->questiontext;
        $questiontitle = $thisQuestion->questiontitle;
        $userid = $thisQuestion->userid;
        $votesum = $thisQuestion->votesum;
        $tag = $thisQuestion->tag;
        $created = $thisQuestion->created;

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Redigera fråga",
            ],
            [
                "id" => [
                    "value"       => $id,
                    "type"        => "hidden",
                ],
                "userid" => [
                    "type"        => "hidden",
                    "value"       => $userid,
                ],
                "votesum" => [
                    "type"        => "hidden",
                    "value"       => $votesum,
                ],
                "tag" => [
                    "type"        => "hidden",
                    "value"       => $tag,
                ],
                "created" => [
                    "type"        => "hidden",
                    "value"       => $created,
                ],
                "questiontitle" => [
                    "type"        => "text",
                    "value"       => $questiontitle,
                ],
                "questiontext" => [
                    "type"        => "textarea",
                    "value"       => $questiontext,
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
        $questiontitle    = $this->form->value("questiontitle");
        $questiontext    = $this->form->value("questiontext");
        $userid    = $this->form->value("userid");
        $votesum    = $this->form->value("votesum");
        $tag    = $this->form->value("tag");
        $created    = $this->form->value("created");

        // Save to database
        $question = new Question();
        $question->setDb($this->di->get("db"));

        $question->id = $id;
        $question->questiontitle = $questiontitle;
        $question->questiontext = $questiontext;
        $question->userid = $userid;
        $question->votesum = $votesum;
        $question->tag = $tag;
        $question->created = $created;

        $question->updated = date("G:i:s M jS Y", time());

        $question->save();
        $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
        $this->form->addOutput(
            "<div class='alert alert-success alert-dismissable'>"
            . $link .
            "Fråga uppdaterades!</div>"
        );

        return true;
    }
}
