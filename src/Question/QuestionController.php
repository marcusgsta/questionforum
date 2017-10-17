<?php

namespace Marcusgsta\Question;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Anax\User\User;
use \Anax\TextFilter\TextFilter;
use \Marcusgsta\Question\HTMLForm\CreateQuestionForm;
use \Marcusgsta\Answer\HTMLForm\CreateAnswerForm;
use \Marcusgsta\Comment\CommentController;
use \Marcusgsta\HTMLForm\CreateCommentForm;

/**
 * A class for everything to do with questions.
 */
class QuestionController implements InjectionAwareInterface
{
    use InjectionAwareTrait;


    /**
    * Get questions from table
    */
    public function getAllQuestions()
    {
        $title      = "De senaste frågorna";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

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
        // Get user object and add as key to each question object
        $newArray = array_filter($questions, function ($obj) {
            $user = new User;
            $user->setDb($this->di->get("db"));
            $obj->user = $user->getUser($obj->userid);
            return true;
        });
        $questions = $newArray;

        $view->add("take1/questions", $questions);

        $pageRender->renderPage(["title" => $title]);
    }

    /**
    * Get and show a question from table
    */
    public function getShowQuestion($id)
    {
        $title      = "Sida för en fråga";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        $question = new Question();
        $question->setDb($this->di->get("db"));
        $question = $question->find("id", $id);

        // escape output
        $question->questiontext = htmlspecialchars($question->questiontext);

        // filter output with filters
        $textfilter = new TextFilter;
        $question->questiontext = $textfilter->parse($question->questiontext, ["markdown"]);

        // Get user object and add as key to the question object
        $user = new User;
        $user->setDb($this->di->get("db"));
        $question->user = $user->getUser($question->userid);

        // set questionid variable
        $questionid = $question->id;

        // COMMENT-FORM-QUESTION

        // call function to get createCommentForm for question
        $answerid = null;
        $commentFormQuestion = $this->postShowCommentForm($questionid, $answerid);
        // call function to get (escaped and filtered) comments
        // for both questions and answers
        $allComments = $this->di->get("commentController");
        $allComments = $allComments->getComments();

        // filter out comments for questions
        $newArray = array_filter($allComments, function ($obj) {

            // Get user object and add as key to the commentsQuestion object
            $user = new User;
            $user->setDb($this->di->get("db"));
            $obj->user = $user->getUser($obj->userid);

            // return comments that have a questionid
            return $obj->questionid != null;
        });
        $commentsQuestion = $newArray;

        // ANSWERS PART

        // ANSWER-FORM

        // call function to get createAnswerForm

        $answerForm = $this->postCreateAnswer($questionid);

        // call function to get all answers to questionid
        $answer = $this->di->get("answerController");
        $answers = $answer->getAnswers($questionid);


        // COMMENTS AND COMMENT-ANSWER-FORM
        // Filter array of answers to
        $newArray = array_filter($answers, function ($obj) {
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
                $user = new User;
                $user->setDb($this->di->get("db"));
                $obj->user = $user->getUser($obj->userid);
            });
        });



        $data = [
            "question" => $question,
            "commentsQuestion" => $commentsQuestion,
            "commentFormQuestion" => $commentFormQuestion,
            "answerForm" => $answerForm,
            "answers" => $answers
        ];

        $view->add("take1/question", $data);

        $pageRender->renderPage(["title" => $title]);
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
}
