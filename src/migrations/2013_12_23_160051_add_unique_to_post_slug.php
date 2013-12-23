<?php

use Illuminate\Database\Migrations\Migration;

class AddUniqueToPostSlug extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::table('posts', function($t) {
	      $t->unique('slug');
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::table('posts', function($t) {
	      $t->dropUnique('slug');
	    });
	}

}
