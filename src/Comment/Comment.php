<?php

namespace Marcusgsta\Comment;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class Comment extends ActiveRecordModel
{

    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Comment";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $commenttext;
    public $userid;
    public $questionid;
    public $answerid;
    public $created;
    public $updated;
    public $deleted;
    public $active;
    public $votesum;
}
