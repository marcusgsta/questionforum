<?php

namespace Marcusgsta\Question\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Anax\User\UserController;
use \Marcusgsta\Question\Question;
use \Marcusgsta\Tag\Tag;
use \Marcusgsta\Tag\TagQuestion;

/**
 * Example of FormModel implementation.
 */
class CreateQuestionForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di)
    {
        parent::__construct($di);
        $user = new UserController;
        $user->setDI($this->di);
        $user = $user->getLoggedinUser();
        $userid = $user->id;

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Ställ en fråga",
                "class" => "createquestion"
            ],
            [
                "questiontitle" => [
                    "type"        => "text",
                    "name"        => "Fråga"
                ],
                "questiontext" => [
                    "type"        => "textarea",
                    "name"        => "Frågetext"
                ],
                "userid" => [
                    "type"        => "hidden",
                    "value"       => $userid,
                ],
                "tags" => [
                    "type"              => "textarea",
                    "placeholder"       => "cars, philosophy, music-history",
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
        $questiontitle       = $this->form->value("questiontitle");
        $questiontext       = $this->form->value("questiontext");
        $userid            = $this->form->value("userid");
        $tag              = $this->form->value("tags");

        // Save to database

        $question = new Question();
        $question->setDb($this->di->get("db"));

        if (!isset($questiontitle, $questiontext)) {
            $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
            $this->form->addOutput(
                "<div class='alert alert-danger alert-dismissable'>
                . $link . Försök igen. Skrev du både titel och text?</div>"
            );
            return false;
        }

        $question->questiontitle = $questiontitle;
        $question->questiontext = $questiontext;
        $question->userid = $userid;

        $createdDate = date("G:i:s M jS Y", time());
        $question->created = $createdDate;
        if (isset($tag)) {
            $question->tag = $tag;
        }
        $question->save();
        $questionid = $question->id;

        if (isset($tag)) {
            // explode multiple tags to array
            $tags = [];
            $tags = explode(",", $tag);
            // trim whitespace from members of array
            $tags = array_map('trim', $tags);
            //save every tag in new row of table Tag // or save single tag in new row
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $tagInstance = new Tag();
                    $tagInstance->setDb($this->di->get("db"));
                    $tagInstance->tagtext = $tag;
                    // if tag doesn't exist, save as new row in table Tag
                    if (null == $tagInstance->find("tagtext", $tag)) {
                        $tagInstance->save();
                        $tagid = $tagInstance->id;
                    // else find the alreade existing tag's id
                    } else {
                        $tagInstance->find("tagtext", $tag);
                        $tagid = $tagInstance->id;
                    }
                    // save new connection
                    $tagQuestion = new TagQuestion;
                    $tagQuestion->setDb($this->di->get("db"));
                    $tagQuestion->tagid = $tagid;
                    $tagQuestion->questionid = $questionid;
                    $tagQuestion->save();
                }
            } else {
                $tagInstance->tagtext = $tag;
                // if tag doesn't exist, save as new row in table Tag
                if (null == $tagInstance->find("tagtext", $tag)) {
                    $tagInstance->save();
                    $tagid = $tagInstance->id;
                // else find the alreade existing tag's id
                } else {
                    $tagInstance->find("tagtext", $tag);
                    $tagid = $tagInstance->id;
                }
                // save new connection
                $tagid = $tagInstance->id;
                $tagQuestion = new TagQuestion;
                $tagQuestion->setDb($this->di->get("db"));
                $tagQuestion->tagid = $tagid;
                $tagQuestion->questionid = $questionid;
                $tagQuestion->save();
            }
        }

        $link = "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
        $this->form->addOutput(
            "<div class='alert alert-success alert-dismissable'>
            . $link . Tack för din fråga!</div>"
        );
        return true;
    }
}
