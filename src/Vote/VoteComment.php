<?php

namespace Marcusgsta\Vote;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class VoteComment extends ActiveRecordModel
{

    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Votecomment";

    /**
     * Columns in the table.
     *
     */
    public $userid;
    public $commentid;
    public $created;
}
