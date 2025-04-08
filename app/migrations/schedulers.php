<?php

use System\Database\Schema;

class schedulers
{
    public function up()
    {
        Schema::table('schedulers', function($table) {
            $table->int('id');
            $table->string('scheduler_class');
            $table->enum('status', ['pending', 'running', 'completed', 'failed']);
            $table->text('description');
            $table->string('next_run');
            $table->string('last_run');
            $table->datetime('created_at');
            $table->datetime('updated_at');
        });
    }

    public function down()
    {
        // Add rollback logic here
    }
}
