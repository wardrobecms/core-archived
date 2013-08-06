<?php

$wardrobeControllers = 'Wardrobe\Core\Controllers\\';

Route::group(Config::get('core::routes.route_group'), function() use ($wardrobeControllers)
{
	Route::get('/', array('uses' => $wardrobeControllers.'HomeController@index', 'as' => 'wardrobe.index'));
});
