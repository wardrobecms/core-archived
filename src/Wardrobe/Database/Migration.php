<?php namespace Wardrobe\Database;

use Wardrobe\Database\DatabaseManager;
use App;

abstract class Migration extends \Illuminate\Database\Migrations\Migration
{
	/**
	 * Get the migration connection name.
	 *
	 * @return string
	 */
	public function getConnection()
	{
		return App::make('db')->connection('wardrobe');
	}
}
