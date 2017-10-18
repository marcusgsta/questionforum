<?php

namespace Marcusgsta\Tag;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Anax\User\User;
use \Anax\TextFilter\TextFilter;
use \Marcusgsta\Question\HTMLForm\CreateQuestionForm;
use \Marcusgsta\Tag\TagQuestion;
use \Marcusgsta\Question\Question;

/**
 * A class for everything to do with questions.
 */
class TagController implements InjectionAwareInterface
{
    use InjectionAwareTrait;

    /**
    * Get questions from tags
    * @param integer tagid
    * @return array $questions
    */
    public function getQuestions($tagid)
    {
        // Get tag from table Tag with id $tagid
        $tag = new Tag();
        $tag->setDb($this->di->get("db"));
        $tagObject = $tag->find("id", $tagid);
        // call function to get questions
        $tagObject = $this->expandTagObjectWithQuestions($tagObject);
        return $tagObject;
    }

    /**
    * Get questions from tags
    * @param string tag
    * @return array $questions
    */
    public function showQuestions($tagid)
    {
        $tagObject = $this->getQuestions($tagid);

        $tagtext = $tagObject->tagtext;
        $title      = "Frågor med taggen '$tagtext'";

        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        $data = $tagObject;

        $view->add("take1/tag", [$data]);
        $pageRender->renderPage(['title' => $title]);
    }


    /**
    * show tags
    * @param array $tags (flattened, sorted, cleaned)
    *
    */
    public function showTags($tags)
    {
        // $this->expandTagObjectWithQuestions();

        $title      = "Taggar";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $data = $tags;

        // $view->add("default1/article", $data);
        $view->add("take1/tags", $data);
        $pageRender->renderPage(['title' => $title]);
    }

    /**
    *
    * @return array array of tagObjects
    */
    public function getAllTags()
    {
        // Get all tags from table Tag
        $tag = new Tag();
        $tag->setDb($this->di->get("db"));
        $groupby = "tagtext";
        $tagObjects = $tag->findAll($groupby);

        // Get questions for each tag
        foreach ($tagObjects as $tagObject) {
            $tagObject = $this->expandTagObjectWithQuestions($tagObject);
        }
        $this->showTags($tagObjects);
    }

    /**
    * Get questions from tagObject
    * @param object $tagObject
    * @return object $tagObject with $tag->question = array of questions
    */
    public function expandTagObjectWithQuestions($tagObject)
    {
        $question = new Question();
        $question->setDb($this->di->get("db"));

        $table = "Tagquestion";
        $condition = "Question.id = Tagquestion.questionid";
        $where = "Tagquestion.tagid = ?";
        $value = $tagObject->id;
        $tags = $question->findAllWhereJoin($table, $condition, $where, $value);
        $tagObject->question = $tags;
        return $tagObject;
    }


    /**
    * Flattens a multidimensional array
    * @param $array to flatten
    * @return flattened array
    */
    public function flatten($array)
    {
        $return = array();
        array_walk_recursive($array, function ($temp) use (&$return) {
            $return[] = $temp;
        });
        return $return;
    }



    /**
    * get the most popular tags
    *
    * @return array $tags
    */
    public function getPopularTags()
    {
        // Get all tags from table Tag
        $tag = new Tag();
        $tag->setDb($this->di->get("db"));
        $groupby = "tagtext";
        $tagObjects = $tag->findAll($groupby);

        // Get questions for each tag
        foreach ($tagObjects as $tagObject) {
            $tagObject = $this->expandTagObjectWithQuestions($tagObject);
        }

        // Count questions
        //$countedTags = [];
        foreach ($tagObjects as $tagObject) {
                $tagObject->count = count($tagObject->question);
            // $key = $tagObject->tagtext;
            // $countedTags[$key] = count($tagObject->question);
        }

        // Sort according to popularity - descending order
        //arsort($countedTags);
        //usort($tagObjects, array($this, "cmp"));
        usort($tagObjects, function ($one, $two) {
            return strcmp($two->count, $one->count);
        });

        return $tagObjects;
    }


    /**
    * @param integer questionid
    * @return array array of tagObjects
    */
    public function getQuestionTags($questionid)
    {
        $tag = new Tag();
        $tag->setDb($this->di->get("db"));
        // $sql = "SELECT * FROM Tag INNER JOIN TagQuestion
        // ON Tag.questionid = Tagquestion.questionid";
        $table = "Tagquestion";
        $condition = "Tag.id = Tagquestion.tagid";
        $where = "Tagquestion.questionid = ?";
        $value = $questionid;

        $questionTags = $tag->findAllWhereJoin($table, $condition, $where, $value);

        return $questionTags;
    }
}
