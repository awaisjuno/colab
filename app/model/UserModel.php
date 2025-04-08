<?php

namespace App\Model;

use System\Model;

class UserModel extends Model
{
    protected $primaryKey = 'id';
    protected $timestamps = true;

    public function insertLogin($login)
    {
        $this->insert('user', $login);
        return $this->lastInsertId();
    }

    public function insertUserDetails($userDetail)
    {
        return $this->insert('user_detail', $userDetail);
    }

    public function findLogin($email, $password)
    {
        return $this->select('user', [
            'email' => $email,
            'password' => $password
        ], 1);
    }
}
