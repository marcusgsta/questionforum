<?php

namespace Anax\User;

use \Anax\Configure\ConfigureInterface;
use \Anax\Configure\ConfigureTrait;
use \Anax\DI\InjectionAwareInterface;
use \Anax\Di\InjectionAwareTrait;
use \Anax\User\HTMLForm\UserLoginForm;
use \Anax\User\HTMLForm\CreateUserForm;
use \Anax\User\HTMLForm\EditUserForm;
use \Anax\User\HTMLForm\DeleteUserForm;
use \Marcusgsta\Question\Question;
use \Marcusgsta\Answer\Answer;
use \Marcusgsta\Comment\Comment;
use \Marcusgsta\Vote\VoteAnswer;
use \Marcusgsta\Vote\VoteComment;
use \Anax\TextFilter\TextFilter;

/**
 * A controller class.
 */
class UserController implements
    ConfigureInterface,
    InjectionAwareInterface
{
    use ConfigureTrait,
        InjectionAwareTrait;



    /**
     * @var $data description
     */
    //private $data;



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function getPostLogin()
    {
        $title      = "A login page";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form       = new UserLoginForm($this->di);

        $form->check();


        $data = [
            "content" => $form->getHTML(),
        ];

        $view->add("default2/article", $data);

        $pageRender->renderPage(["title" => $title]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return $userobj
     */
    public function getLoggedinUser()
    {
        $acronym = $this->di->session->get("user");

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("acronym", $acronym);
        return $user;
    }


    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function getPostCreateUser()
    {
        $title      = "A create user page";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form       = new CreateUserForm($this->di);

        $form->check();

        $data = [
            "content" => $form->getHTML(),
        ];

        $view->add("default2/article", $data);

        $pageRender->renderPage(["title" => $title]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function editAllUsers()
    {
        $acronym = $this->di->session->get("user");
        $role = $this->di->get("commentController")->getRole($acronym);
        if ($role != 10) {
            echo "Du har ej tillgång till sidan.";
            return false;
        }

        $title      = "An edit all users page";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form       = new DeleteUserForm($this->di);

        $form->check();

        $data = [
            "content" => $form->getHTML(),
        ];

        $view->add("default2/article", $data);

        $pageRender->renderPage(["title" => $title]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function showProfile()
    {
        $acronym = $this->di->session->get("user");
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user = $user->find("acronym", $acronym);

        if (isset($user->email)) {
            $this->editUser($user->id);
        }
        // if no user is logged in:

        $val = $this->di->get("url")->createRelative("user/login");
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $data = [
            "content" => "<h2><a href='$val'>Logga in</a> för att se din profil.</h2>"
        ];

        $view->add("default2/article", $data);
        $pageRender->renderPage(["title" => "Ej tillgång"]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function editUser($id)
    {
        $title      = "Redigera användare";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        $user = new User;
        $user->setDb($this->di->get("db"));
        $user = $user->find("id", $id);

        $data = [
            "title" => $title,
            "email" => $user->email,
            "acronym" => $user->acronym,
            "gravatar" => $user->gravatar,
            "role" => $user->role,
        ];

        $form       = new EditUserForm($this->di, $user->acronym);
        $form->check();

        $formdata = [
            "content" => $form->getHTML(),
        ];

        $view->add("take1/profile", $data);
        $view->add("default2/article", $formdata);

        $pageRender->renderPage(["title" => $title]);
        return true;
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function editOneUser($id)
    {
        $title      = "Redigera en användare";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        $user = new User;
        $user->setDb($this->di->get("db"));
        $user = $user->find("id", $id);

        $form       = new EditUserForm($this->di, $user->acronym);
        $form->check();

        $formdata = [
            "content" => $form->getHTML(),
        ];

        $view->add("default2/article", $formdata);

        $pageRender->renderPage(["title" => $title]);
        return true;
    }

    /**
    * get user object from userid
    * @param integer $userid
    *
    * @return object $user
    */
    public function getUser($userid)
    {
        $user = new User;
        $user->setDb($this->di->get("db"));
        $user = $user->find("id", $userid);
        return $user;
    }

    /**
    * get all user objects
    *
    *
    * @return array $users
    */
    public function getUsers()
    {
        $user = new User;
        $user->setDb($this->di->get("db"));
        $orderby = "acronym ASC";
        $users = $user->findAll($orderby);
        return $users;
    }

    /**
    * show a user's public profile (questions / answers etc)
    * @param integer $userid
    *
    * @return void
    */
    public function showpublicProfile($userid)
    {
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        $user = $this->getUser($userid);

        $questions = $this->getUserQuestions($userid);
        $answers = $this->getUserAnswers($userid);
        $comments = $this->getUserComments($userid);

        foreach ($answers as $answer) {
            $question = $this->getQuestionFromid($answer->questionid);

            if (isset($question)) {
                $answeredQuestions[] = $question;
            }
        }

        $user->questions = $questions;
        $user->answeredQuestions = isset($answeredQuestions) ? $answeredQuestions : [];

        $user->answers = $answers;

        $user->comments = $comments;
        $user->votesMade = $this->getUserVoteCount($userid);

        $acronym = $user->acronym;
        $title = $acronym . " – offentlig profil";
        $data = $user;
        $view->add("take1/publicprofile", [$data]);
        $pageRender->renderPage(["title" => $title]);
        return true;
    }


    /**
    * get a users questions
    * @param integer $userid
    * @return array $questionobjects
    */
    public function getUserQuestions($userid)
    {
        $question = new Question;
        $question->setDb($this->di->get("db"));
        $where = "userid = ?";
        $value = $userid;
        $questions = $question->findAllWhere($where, $value);

        $questions = array_filter($questions, function ($obj) {
            $obj->questiontitle = htmlspecialchars($obj->questiontitle);
            // filter output with filters
            $textfilter = new TextFilter;
            $obj->questiontitle = $textfilter->parse($obj->questiontitle, ["markdown"]);
            return true;
        });
        return $questions;
    }

    /**
    * get a users answered questions
    * @param integer $userid
    * @return array $answerobjects
    */
    public function getUserAnsweredQuestions($userid)
    {
        $answer = new Answer;
        $answer->setDb($this->di->get("db"));
        $where = "userid = ?";
        $value = $userid;
        $answers = $answer->findAllWhere($where, $value);

        $newArray = array_filter($answers, function ($obj) {
            $obj->answertitle = htmlspecialchars($obj->answertitle);
            // filter output with filters
            $textfilter = new TextFilter;
            $obj->answertitle = $textfilter->parse($obj->answertitle, ["markdown"]);
            return true;
        });
        $answers = $newArray;

        return $answers;
    }

    /**
    * get a users answers
    * @param integer $userid
    * @return array $questionobjects
    */
    public function getUserAnswers($userid)
    {
        $answer = new Answer;
        $answer->setDb($this->di->get("db"));
        $where = "userid = ?";
        $value = $userid;
        $answers = $answer->findAllWhere($where, $value);

        $newArray = array_filter($answers, function ($obj) {
            $obj->answertitle = htmlspecialchars($obj->answertitle);
            // filter output with filters
            $textfilter = new TextFilter;
            $obj->answertitle = $textfilter->parse($obj->answertitle, ["markdown"]);
            return true;
        });
        $answers = $newArray;

        return $answers;
    }


    /**
    * Get comments from userid
    * @param integer userid
    * @return array comments
    */
    public function getUserComments($userid)
    {
        $comments = new Comment();
        $comments->setDb($this->di->get("db"));
        $where = "userid = ?";
        $value = $userid;
        $commentObjects = $comments->findAllWhere($where, $value);
        // escape output
        $newArray = array_filter($commentObjects, function ($obj) {
            // escape output
            $obj->commenttext = htmlspecialchars($obj->commenttext);
            // filter output with filters
            $textfilter = new TextFilter;
            $obj->commenttext = $textfilter->parse($obj->commenttext, ["markdown"]);
            // create excerpts
            $obj->excerpt = substr($obj->commenttext->text, 0, 100) . " [. . .]";
            // get questionid from answerid (for linking)
            if (isset($obj->answerid)) {
                $answer = $this->di->get("answerController");
                $question = $answer->getQuestion($obj->answerid);
                $obj->questionid = $question->id;
            }
            return true;
        });
        $commentObjects = $newArray;

        return $commentObjects;
    }

    /**
    * get a question from questionid
    * @param integer $questionid
    * @return array $questionobjects
    */
    public function getQuestionFromid($questionid)
    {
        $question = new Question;
        $question->setDb($this->di->get("db"));
        $where = "id = ?";
        $value = $questionid;
        $question = $question->findWhere($where, $value);
        return $question;
    }

    /**
    * show all users
    *
    * @return void
    */
    public function getShowUsers()
    {
        $userObjects = $this->getUsers();
        $view = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        $title = "Alla användare";
        $data = $userObjects;
        $view->add("take1/users", $data);
        $pageRender->renderPage(['title' => $title]);
        return true;
    }


    /**
    * get all votes made by userid
    * @param integer userid
    * @return integer count of votes
    **/
    public function getUserVoteCount($userid)
    {
        $questionVotes = $this->di->get("voteController")->getQuestionVotes($userid);
        $answerVotes = $this->getAnswerVotes($userid);
        $commentVotes = $this->getCommentVotes($userid);

        $voteCount = count($questionVotes) + count($answerVotes) + count($commentVotes);

        return $voteCount;
    }


    // /**
    // * get all votes made by user on questions
    // * @param integer userid
    // * @return array votes
    // **/
    // public function getQuestionVotes($userid)
    // {
    //     $votequestion = new VoteQuestion();
    //     $votequestion->setDb($this->di->get("db"));
    //     $where = "userid = ?";
    //     $value = $userid;
    //     $votes = $votequestion->findAllWhere($where, $value);
    //     return $votes;
    // }


    /**
    * get all votes made by user on answers
    * @param integer userid
    * @return array votes
    **/
    public function getAnswerVotes($userid)
    {
        $voteanswer = new VoteAnswer();
        $voteanswer->setDb($this->di->get("db"));
        $where = "userid = ?";
        $value = $userid;
        $votes = $voteanswer->findAllWhere($where, $value);
        return $votes;
    }


    /**
    * get all votes made by user on comments
    * @param integer userid
    * @return array votes
    **/
    public function getCommentVotes($userid)
    {
        $votecomment = new VoteComment();
        $votecomment->setDb($this->di->get("db"));
        $where = "userid = ?";
        $value = $userid;
        $votes = $votecomment->findAllWhere($where, $value);
        return $votes;
    }
}
