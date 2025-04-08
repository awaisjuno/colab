<?php

use System\Database\Schema;

class User
{
    public function up()
    {
        Schema::table('user', function($table) {
            $table->id('user_id');
            $table->string('email');
            $table->text('password');
            $table->int('status');
            $table->int('is_active');
            $table->int('is_delete');
        });
    }

    public function down()
    {
        // Add rollback logic here
    }
}
