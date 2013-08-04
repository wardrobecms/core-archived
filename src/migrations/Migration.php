<?php

abstract class WardrobeMigration extends Illuminate\Database\Migrations\Migration
{
	/**
	 * Get the migration connection name.
	 *
	 * @return string
	 */
	public function getConnection()
	{
		return DatabaseManager::getConnection();
	}
}
