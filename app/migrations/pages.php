<?php

use System\Database\Schema;

class pages
{
    public function up()
    {
        Schema::table('page', function($table) {
            $table->int('page_id');
            $table->string('page_name');
            $table->string('page_route');
            $table->string('page_title');
            $table->string('page_description');
            $table->string('page_keywords');
        });
    }

    public function down()
    {
        // Add rollback logic here
    }
}
