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
                Schema::table('tags', function($t) {
                        $t->increments('id');
                });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
                Schema::table('tags', function($t) {
                        $t->dropColumn('id');
                });
        }

}
