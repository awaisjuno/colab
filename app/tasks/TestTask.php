<?php

namespace App\Tasks;

class TestTask
{
    public function execute()
    {
        echo "Test task executed at " . (new \DateTime())->format('Y-m-d H:i:s') . "\n";
    }
}