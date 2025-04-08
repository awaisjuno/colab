<?php

use System\Database\Schema;

class UserDetail
{
    public function up()
    {
        Schema::table('user_detail', function($table) {
            $table->int('user_detail_id');  // Auto-increment primary key
            $table->int('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('designation');
            $table->string('mobile');
            $table->string('register_date');
            $table->int('is_active'); // Active status (ENUM as string)
            $table->int('is_delete');

            // Add foreign key constraint
            $table->foreign('user_id', 'user', 'user_id');
        });
    }

    public function down()
    {
        // Add rollback logic here
    }
}
