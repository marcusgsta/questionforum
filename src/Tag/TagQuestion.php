<?php

namespace Marcusgsta\Tag;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class TagQuestion extends ActiveRecordModel
{

    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Tagquestion";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $tagid;
    public $questionid;
}
