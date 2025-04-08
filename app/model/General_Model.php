<?php

namespace App\Model;

use System\Database\Connection;
use System\Driver\Database;
use System\Model;

class General_Model extends Model
{
    protected $table = 'page';
    protected $primaryKey = 'id';
    protected $timestamps = true;

    protected $db;

    public function __construct($connectionName = 'default')
    {
        $this->db = new Connection($connectionName);
    }

    public function PageDetail($route)
    {
        return $this->db->selectOne('page', [
            'page_route' => $route,
        ]);
    }


}
