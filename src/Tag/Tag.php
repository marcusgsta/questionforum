<?php

namespace Marcusgsta\Tag;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class Tag extends ActiveRecordModel
{

    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Tag";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $tagtext;
    public $created;
}
