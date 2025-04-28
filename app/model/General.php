<?php

namespace App\Model;

use System\Model;

class General extends Model
{
    protected $table = 'general';
    protected $primaryKey = 'id';
    protected $timestamps = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function insertData(array $data)
    {
        return $this->insert($this->table, $data);
    }

    public function updateData($id, array $data)
    {
        return $this->update($this->table, $data, ['id' => $id]);
    }

    public function deleteData($id)
    {
        return $this->delete($this->table, ['id' => $id]);
    }

    public function selectData(array $conditions = [])
    {
        return $this->select($this->table, $conditions);
    }

    public function findData($id)
    {
        return $this->select($this->table, ['id' => $id])->first();
    }
}
