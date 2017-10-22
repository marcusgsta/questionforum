<?php

namespace Marcusgsta\Question;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Anax\User\User;
use \Anax\TextFilter\TextFilter;
use \Marcusgsta\Question\HTMLForm\CreateQuestionForm;
use \Marcusgsta\Question\HTMLForm\EditQuestionForm;
use \Marcusgsta\Answer\HTMLForm\CreateAnswerForm;
use \Marcusgsta\Comment\CommentController;
use \Marcusgsta\HTMLForm\CreateCommentForm;
use \Marcusgsta\Tag\TagController;
use \Marcusgsta\Vote\VoteQuestion;

/**
 * A class for everything to do with questions.
 */
class QuestionController implements InjectionAwareInterface
{
    use InjectionAwareTrait;


    /**
    * Get questions from table
    */
    public function getShowAllQuestions()
    {
        $title      = "De senaste frågorna";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        $questions = $this->getAllQuestions();

        $view->add("take1/questions", $questions);

        $pageRender->renderPage(["title" => $title]);
    }

    /**
    * Get anonymized user
    * (if deleted >= now())
    * @param integer userid
    * @return object user
    **/
    // public function anonymizeUser($userid)
    // {
    //     $user = new User();
    //     $user->acronym = "Deleted User";
    //     $user->email = "deleted@nomail.com";
    //     $user->gravatar = "";
    //     $user->rank = 0;
    //     return $user;
    // }



    public function getAllQuestions()
    {
        $question = new Question();
        $question->setDb($this->di->get("db"));
        $orderby = "id DESC";
        $questions = $question->findAll($orderby);

        // escape output

        $newArray = array_filter($questions, function ($obj) {
            $obj->questiontext = htmlspecialchars($obj->questiontext);
            return true;
        });
        $questions = $newArray;

        // filter output with filters

        $newArray = array_filter($questions, function ($obj) {
            $textfilter = new TextFilter;
            $obj->questiontitle = $textfilter->parse($obj->questiontitle, ["markdown"]);
            $obj->questiontext = $textfilter->parse($obj->questiontext, ["markdown"]);
            // add excerpt to object
            $obj->excerpt = substr($obj->questiontext->text, 0, 500);
            return true;
        });


        $questions = $newArray;

        $newArray = array_filter($questions, function ($obj) {
            // get answers and add to question object
            $answer = $this->di->get("answerController");
            $answers = $answer->getAnswers($obj->id);
            $obj->answers = $answers;
            $obj->answerCount = count($answers);

            // Get user object and add as key to each question object
            $user = new User;
            $user->setDb($this->di->get("db"));
            $obj->user = $user->getUser($obj->userid);
            return true;
        });
        $allQuestions = $newArray;

        return $allQuestions;
    }

    /**
    * escape and filter a question
    * @param object questionobject
    * @return object question
    **/
    public function escapeFilterQuestion($question)
    {


        // escape output
        $question->questiontitle = htmlspecialchars($question->questiontitle);
        $question->questiontext = htmlspecialchars($question->questiontext);


        // filter output with filters
        $textfilter = new TextFilter;
        $question->questiontitle = $textfilter->parse($question->questiontitle, ["markdown"]);
        $question->questiontext = $textfilter->parse($question->questiontext, ["markdown"]);

        return $question;
    }



    /**
    * get a question from id
    * @param integer questionid
    * @return object question
    **/
    public function getQuestion($id)
    {
        $question = new Question();
        $question->setDb($this->di->get("db"));
        $question = $question->find("id", $id);
        return $question;
    }


    /**
    * show a question
    * @param integer questionid
    * @return object question
    **/
    public function showQuestion($data)
    {
        $title      = "Sida för en fråga";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        $view->add("take1/question", $data);
        $pageRender->renderPage(["title" => $title]);
    }

    /**
    * Get and show a question from table
    */
    public function getShowQuestion($id, $sortType = null)
    {
        $question = $this->getQuestion($id);
        $textfilter = new TextFilter;

        $question = $this->escapeFilterQuestion($question);

        // Get user object and add as key to the question object
        $question->user = $this->di->get("userController")->getUser($question->userid);

        // set questionid variable
        $questionid = $question->id;

        // Get tag objects and add as key to the question object
        $tags = $this->di->get("tagController");
        $tags = $tags->getQuestionTags($questionid);

        foreach ($tags as $tag) {
            $tag->tagtext = htmlspecialchars($tag->tagtext);
            $tag->tagtext = $textfilter->parse($tag->tagtext, ["markdown"]);
        }
        $question->tags = $tags;
        // COMMENT-FORM-QUESTION
        // call function to get createCommentForm for question
        $answerid = null;
        $commentFormQuestion = $this->postShowCommentForm($questionid, $answerid);
        // call function to get (escaped and filtered) comments
        $allComments = $this->di->get("commentController");
        $allComments = $allComments->getComments();
        // filter out comments for question
        $newArray = array_filter($allComments, function ($obj) use ($questionid) {
            // Get user object and add as key to the commentsQuestion object
            $obj->user = $this->di->get("userController")->getUser($obj->userid);
            // check if user has voted for comment
            $comment = $this->di->get("commentController");
            $obj->userHasVoted = $comment->userHasVoted($obj->id);
            // return comments for questionid
            return $obj->questionid == $questionid;
        });
        $commentsQuestion = $newArray;

        // ANSWERS PART // ANSWER-FORM
        $answerForm = $this->postCreateAnswer($questionid);
        // call function to get all answers to questionid
        $answer = $this->di->get("answerController");
        $answers = $answer->getAnswers($questionid);
        // sort answer
        if (isset($sortType)) {
            $answers = $answer->sortAnswers($answers, $sortType);
        }
        // COMMENTS AND COMMENT-ANSWER-FORM
        // Filter array of answers to
        $newArray = array_filter($answers, function ($obj) use ($question) {
            // get comments for each answer
            $commentsAnswer = $this->di->get("commentController");

            $commentsAnswer = $commentsAnswer->getAnswerComments($obj->id);

            //create a commentform for every answer
            $questionid = null; // set questionid to null
            $commentForm = $this->postShowCommentForm($questionid, $obj->id);
            // Add commentform to its answerObject
            $obj->commentForm = $commentForm;
            // Add commentsAnswer to its answerObject
            $obj->commentsAnswer = $commentsAnswer;
            // Get user object and add as key to each commentObject
            $newArray = array_filter($obj->commentsAnswer, function ($obj) {
                $obj->user = $this->di->get("userController")->getUser($obj->userid);
                // check if user has voted for comment
                $comment = $this->di->get("commentController");
                $obj->userHasVoted = $comment->userHasVoted($obj->id);
            });
            // check if answer is accepted
            $answer = $this->di->get("answerController");
            $obj->accepted = $answer->isAccepted($obj->id);
            // get answerButton
            $obj->acceptButton = $answer->userCanAccept($question);
            // check if user has voted for answer
            $answer = $this->di->get("answerController");
            $obj->userHasVoted = $answer->userHasVoted($obj->id);
        });
        // check if question has an accepted answer
        $answer = $this->di->get("answerController");
        $question->hasAccepted = $answer->hasAccepted($questionid);
        $question->userHasVoted = $this->userHasVoted($questionid);

        $data = [
            "question" => $question,
            "commentsQuestion" => $commentsQuestion,
            "commentFormQuestion" => $commentFormQuestion,
            "answerForm" => $answerForm,
            "answers" => $answers
        ];

        $this->showQuestion($data);
    }

    /**
    * Create answer with HTMLForm
    *
    * @return void
    */
    public function postCreateAnswer($questionid)
    {
        // $this->di->get("loginController")->anyLoggedin() ? true : $this->di->get("response")->redirect("user/login");

        $form       = new CreateAnswerForm($this->di, $questionid);
        $form->check();
        return $form->getHTML();
    }

    /**
    * Create question with HTMLForm
    */
    public function postCreateQuestion()
    {
        $this->di->get("loginController")->anyLoggedin() ? true : $this->di->get("response")->redirect("user/login");

        $title      = "A create question page";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form       = new CreateQuestionForm($this->di);

        $form->check();

        $data = [
            "content" => $form->getHTML(),
        ];

        $view->add("default2/article", $data);

        $pageRender->renderPage(["title" => $title]);
    }

    /**
    * Show Comment Form
    *
    */
    public function postShowCommentForm($questionid, $answerid)
    {
        // $this->di->get("loginController")->anyLoggedin() ? true : $this->di->get("response")->redirect("user/login");

        $form       = new CreateCommentForm($this->di, $questionid, $answerid);
        $form->check();
        return $form->getHTML();
    }


    /**
    * Show Index Page
    *
    */
    public function showIndexPage()
    {
        $title      = "Ett forum om allt mellan himmel och jord";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        $latestQuestions = $this->getLatestQuestions();

        $popularTags = $this->di->get("tagController");
        $popularTags = $popularTags->getPopularTags();

        //$users = $this->di->get("userController");
        $mostActiveUsers = $this->getMostActiveUsers();

        $data = [
            "latestQuestions" => $latestQuestions,
            "popularTags" => $popularTags,
            "mostActiveUsers" => $mostActiveUsers
        ];

        $view->add("take1/index", $data);

        $pageRender->renderPage(["title" => $title]);
    }


    /**
    * get the 4 latest questions
    *
    * @return array $questions
    */
    public function getLatestQuestions()
    {
        $allQuestions = $this->getAllQuestions();
        $latestQuestions = array_slice($allQuestions, 0, 3);

        return $latestQuestions;
    }


    /**
    * get the most active users
    *
    * @return array $questions
    */
    public function getMostActiveUsers()
    {
        $questions = $this->getAllQuestions();

        $user = $this->di->get("userController");
        $users = $user->getUsers();

        foreach ($users as $user) {
            foreach ($questions as $question) {
                if ($question->userid == $user->id) {
                    $user->count = isset($user->count) ? $user->count : 0;
                    $user->count += 1;
                };
            }
        }
        return $users;
    }


    /**
    * vote a question up or down
    * @param integer questionid
    * @param integer score
    * @return void
    **/
    public function vote($questionid, $score)
    {
        // Get userid
        $user = $this->di->get("userController")->getLoggedinUser();
        $userid = $user->id;

        $question = $this->getQuestion($questionid);
        $question->setDb($this->di->get("db"));
        $question->find("id", $questionid);
        // update
        //$sql = "UPDATE question SET votesum = votesum + $score";
        if ($score == 1) {
            $question->votesum = $question->votesum + 1;
            $user = $this->di->get("userController")->getUser($question->userid);
            $user->rank = $user->rank + 1;
            $user->save();
        } elseif ($score == 0) {
            $question->votesum = $question->votesum - 1;
            $user = $this->di->get("userController")->getUser($question->userid);
            $user->rank = $user->rank - 1;
            $user->save();
        }
        $question->save();

        // update table Votequestion to track vote
        $votequestion = new VoteQuestion();
        $votequestion->setDb($this->di->get("db"));
        $votequestion->userid = $userid;
        $votequestion->questionid = $questionid;
        $votequestion->save();

        // redirect to previous page
        $url = $this->di->get("request")->getServer('HTTP_REFERER');
        $url = $url . "#question-" . $questionid;
        $this->di->get("response")->redirect($url);
        return true;

        // redirect to previous page
        // $url = $this->di->get("request")->getServer('HTTP_REFERER');
        // $url = $url . "#question-" . $questionid;
        // $this->di->get("response")->redirect($url);
        // return true;
    }

    /**
    * Check if user has voted for question
    *
    **/
    public function userHasVoted($questionid)
    {
        $user = $this->di->get("userController")->getLoggedinUser();
        $userid = $user->id;

        $votequestion = new VoteQuestion();
        $votequestion->setDb($this->di->get("db"));

        $where = "userid = ? AND questionid = ?";
        $value = [$userid, $questionid];
        $hasVoted = $votequestion->findWhere($where, $value);

        return $hasVoted = $hasVoted == true ? true : false;
    }



    /**
    * Edit question
    * @param $questionid
    *
    **/
    public function editQuestion($questionid)
    {
        $title      = "A create question page";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        //$question = $this->getQuestion($questionid);
        $form       = new EditQuestionForm($this->di, $questionid);
        $form->check();
        // return $form->getHTML();

        $data = [
            "content" => $form->getHTML(),
        ];

        $view->add("default2/article", $data);

        $pageRender->renderPage(["title" => $title]);
    }
}
