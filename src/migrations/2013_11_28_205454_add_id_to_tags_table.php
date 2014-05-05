<?php

use Illuminate\Database\Migrations\Migration;

class AddIdToTagsTable extends Migration {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
                Schema::create('tags_new', function($table)
                {
                    $table->increments('id');
                    $table->integer('post_id');
                    $table->string('tag');
                    $table->unique(array('post_id', 'tag'));
                });
                DB::statement('INSERT INTO tags_new(`post_id`, `tag`) SELECT `post_id`, `tag` FROM tags');
                Schema::drop('tags');
                Schema::rename('tags_new', 'tags');
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
                Schema::create('tags_old', function($table)
                {
                    $table->integer('post_id');
                    $table->string('tag');
                    $table->unique(array('post_id', 'tag'));
                });
                DB::statement('INSERT INTO tags_old(`post_id`, `tag`) SELECT `post_id`, `tag` FROM tags');
                Schema::drop('tags');
                Schema::rename('tags_old', 'tags');
        }

}
