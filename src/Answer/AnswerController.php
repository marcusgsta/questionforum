<?php

namespace Marcusgsta\Answer;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Anax\User\User;
use \Anax\TextFilter\TextFilter;
use \Marcusgsta\Question\HTMLForm\CreateQuestionForm;
use \Marcusgsta\Answer\HTMLForm\CreateAnswerForm;
use \Marcusgsta\Vote\VoteAnswer;

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
            $obj->answertitle = htmlspecialchars($obj->answertitle);
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


    /**
    * Sort answers
    * @param array answers
    * @return array sorted answers
    **/
    public function sortAnswers($answers, $sortType)
    {
        // sort array of answers according to votes
        if ($sortType == "sortedvotes") {
            // Sort according to votesum - descending order
            //arsort($countedTags);
            //usort($tagObjects, array($this, "cmp"));
            usort($answers, function ($one, $two) {
                return strcmp($two->votesum, $one->votesum);
            });
        };

        // sort array of answers oldest first
        if ($sortType == "sortedoldest") {
            // Sort according to date - oldest first
            usort($answers, function ($one, $two) {
                return strcmp($one->created, $two->created);
            });
        };

        // sort array of answers newest first
        if ($sortType == "sortednewest") {
            // Sort according to date - newest first
            usort($answers, function ($one, $two) {
                return strcmp($two->created, $one->created);
            });
        };

        return $answers;
    }

    /**
    * @param integer answerid
    * @return object answer
    */
    public function getAnswer($answerid)
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("db"));
        $where = "id = ?";
        $answer = $answer->findWhere($where, $answerid);
        return $answer;
    }


    /**
    * check if answer is accepted
    * @param $answerid
    * @return boolean
    **/
    public function isAccepted($answerid)
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("db"));
        $where = "id = ?";
        $answer = $answer->findWhere($where, $answerid);

        return $answer->accepted == 1 ? true : false;
    }


    /**
    * check if a question has an accepted answer
    * @param $questionid
    * @return boolean
    **/
    public function hasAccepted($questionid)
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("db"));
        $where = "questionid = ? AND accepted = ?";
        $value = [$questionid, 1];
        $answer = $answer->findWhere($where, $value);

        return $answer == true ? true : false;
    }

    /**
    *
    *
    **/
    public function userCanAccept($question)
    {
        // get logged in user
        $user = $this->di->get("userController")->getLoggedinUser();
        $userid = $user->id;
        // has user posed the question?
        // compare userid in question
        if ($question->userid == $userid) {
            return true;
        }
        return false;
    }


    /**
    *
    *
    **/
    public function acceptAnswer($answerid)
    {
        // get answerObject from answerid
        $answer = $this->getAnswer($answerid);
        // update
        //$sql = "UPDATE answer SET accepted = 1";

        $answer->setDb($this->di->get("db"));
        $answer->accepted = 1;
        $answer->save();

        // redirect to previous page
        $url = $this->di->get("request")->getServer('HTTP_REFERER');
        $this->di->get("response")->redirect($url);
        return true;
    }


    /**
    * Check if user has voted for answer
    *
    **/
    public function userHasVoted($answerid)
    {
        $user = $this->di->get("userController")->getLoggedinUser();
        $userid = $user->id;

        $voteanswer = new VoteAnswer();
        $voteanswer->setDb($this->di->get("db"));

        $where = "userid = ? AND answerid = ?";
        $value = [$userid, $answerid];
        $hasVoted = $voteanswer->findWhere($where, $value);

        return $hasVoted = $hasVoted == true ? true : false;
    }


    /**
    * vote a answer up or down
    * @param integer questionid
    * @param integer score
    * @return void
    **/
    public function vote($answerid, $score)
    {
        // Get userid
        $user = $this->di->get("userController")->getLoggedinUser();
        $userid = $user->id;

        $answer = $this->getAnswer($answerid);
        $answer->setDb($this->di->get("db"));
        $answer->find("id", $answerid);
        // update
        //$sql = "UPDATE question SET votesum = votesum + $score";
        if ($score == 1) {
            $answer->votesum = $answer->votesum + 1;
        } elseif ($score == 0) {
            $answer->votesum = $answer->votesum - 1;
        }
        $answer->save();

        // update table Voteanswer to track vote
        $voteanswer = new VoteAnswer();
        $voteanswer->setDb($this->di->get("db"));
        $voteanswer->userid = $userid;
        $voteanswer->answerid = $answerid;
        $voteanswer->save();
        // redirect to previous page
        $url = $this->di->get("request")->getServer('HTTP_REFERER');
        $url = $url . "#answer-" . $answerid;
        $this->di->get("response")->redirect($url);
        return true;

        // redirect to previous page
        $url = $this->di->get("request")->getServer('HTTP_REFERER');
        $url = $url . "#answer-" . $answerid;
        $this->di->get("response")->redirect($url);
        return true;
    }
}
