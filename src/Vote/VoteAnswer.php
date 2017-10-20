<?php

namespace Marcusgsta\Vote;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class VoteAnswer extends ActiveRecordModel
{

    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Voteanswer";

    /**
     * Columns in the table.
     *
     */
    public $userid;
    public $answerid;
    public $created;
}
