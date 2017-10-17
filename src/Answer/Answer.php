<?php

namespace Marcusgsta\Answer;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class Answer extends ActiveRecordModel
{

    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Answer";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $answertitle;
    public $answertext;
    public $userid;
    public $questionid;
    public $created;
    public $updated;
    public $deleted;
    public $active;
}
