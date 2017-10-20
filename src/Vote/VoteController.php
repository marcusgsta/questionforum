<?php

namespace Marcusgsta\Vote;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;

/**
 * A class for everything to do with votes.
 */
class VoteController implements InjectionAwareInterface
{
    use InjectionAwareTrait;


    /**
    * get all votes made by user on questions
    * @param integer userid
    * @return array votes
    **/
    public function getQuestionVotes($userid)
    {
        $votequestion = new VoteQuestion();
        $votequestion->setDb($this->di->get("db"));
        $where = "userid = ?";
        $value = $userid;
        $votes = $votequestion->findAllWhere($where, $value);
        return $votes;
    }
}
