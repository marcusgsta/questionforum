<?php

namespace Marcusgsta\Answer;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Anax\User\User;
use \Anax\TextFilter\TextFilter;
use \Marcusgsta\Question\HTMLForm\CreateQuestionForm;
use \Marcusgsta\Answer\HTMLForm\CreateAnswerForm;

/**
 * A class for everything to do with questions.
 */
class AnswerController implements InjectionAwareInterface
{
    use InjectionAwareTrait;


    /**
    * Get answers for a question
    * @param integer $questionid
    */
    public function getAnswers($questionid)
    {
        $answers = new Answer();
        $answers->setDb($this->di->get("db"));
        $where = "questionid = ?";
        $answers = $answers->findAllWhere($where, $questionid);

        // escape output
        $newArray = array_filter($answers, function ($obj) {
            $obj->answertext = htmlspecialchars($obj->answertext);
            return true;
        });
        $answers = $newArray;

        // filter output with filters

        $newArray = array_filter($answers, function ($obj) {
            $textfilter = new TextFilter;
            $obj->answertitle = $textfilter->parse($obj->answertitle, ["markdown"]);
            $obj->answertext = $textfilter->parse($obj->answertext, ["markdown"]);
            return true;
        });
        $answers = $newArray;

        // Get user object and add as key to each answer object
        $newArray = array_filter($answers, function ($obj) {
            $user = new User;
            $user->setDb($this->di->get("db"));
            $obj->user = $user->getUser($obj->userid);
            return true;
        });
        $answers = $newArray;
        return $answers;
    }
}
