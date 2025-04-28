<?php

namespace App\Model;

use System\Model;

class Queries extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertPage($page)
    {
        $this->insert('page', $page);
    }

    public function fetchPages()
    {
        return $this->select('page')->get();
    }

    public function insertTokenAPI($data)
    {
        $this->insert('api_detail', $data);
    }

    public function fetchAPIToken()
    {
        return $this->select('api_detail')->get();
    }

    public function fetchSchedulers()
    {
        return $this->select('schedulers')->get();
    }

    public function fetchUsers()
    {
        return $this->table('user')
            ->join('user_detail', 'user.user_id = user_detail.user_id', 'LEFT')
            ->selectColumns()
            ->get();
    }

    public function del_route($id)
    {
        $this->where('page_id', $id);
        return $this->delete('page');
    }
}
