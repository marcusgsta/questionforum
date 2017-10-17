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

        foreach ($answers as $answer) {
            $questionid = $answer->questionid;
            $questionObject = $this->getQuestionFromid($questionid);
            // if answeredQuestion-array exists
            if (isset($answeredQuestions)) {
                // if iterated questionObject is not in array
                if (!in_array($questionObject, $answeredQuestions)) {
                    // add questionObject to array
                    $answeredQuestions[] = $questionObject;
                }
            } else {
                // if answeredQuestions-array does not exist (yet)
                // add (first) questionObject to array
                $answeredQuestions[] = $questionObject;
            }
        }

        $user->questions = $questions;
        $user->answeredQuestions = $answeredQuestions;
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
        return $questions;
    }

    /**
    * get a users answered questions
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
        return $answers;
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
}
