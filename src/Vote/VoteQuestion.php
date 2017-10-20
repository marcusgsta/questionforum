<?php

namespace Marcusgsta\Vote;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class VoteQuestion extends ActiveRecordModel
{

    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Votequestion";

    /**
     * Columns in the table.
     *
     */
    public $userid;
    public $questionid;
    public $created;
}
