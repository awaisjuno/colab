<?php

namespace System\Helpers;
use System\Model;

class JobDispatcher
{
    public static function dispatch($jobClass, $data)
    {
        $db = new Model();
        $db->insert('jobs', [
            'job_class' => $jobClass,
            'job_data'  => json_encode($data),
            'status'    => 'pending'
        ]);
    }
}
