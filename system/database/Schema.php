<?php

namespace System\Database;

use System\helpers\Table;

class Schema
{
    public static function table($tableName, $callback)
    {
        $table = new Table($tableName);
        $callback($table);
        $table->runQuery();
    }
}