<?php

use System\Database\Schema;

class jobs
{
    public function up()
    {
        Schema::table('jobs', function($table) {
            $table->int('id')->autoIncrement()->primaryKey();
            $table->string('job_class');
            $table->text('job_data');
            $table->enum('status', ['pending', 'processing', 'completed'])->default('pending');
            $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
            $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP');
        });
    }

    public function down()
    {
        // Add rollback logic here (e.g. Schema::drop('jobs');)
    }
}
