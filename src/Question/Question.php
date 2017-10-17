<?php

namespace Marcusgsta\Question;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class Question extends ActiveRecordModel
{

    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Question";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $questiontitle;
    public $questiontext;
    public $userid;
    public $tag;
    public $created;
    public $updated;
    public $deleted;
    public $active;
}
