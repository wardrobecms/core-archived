<?php

$wardrobeControllers = 'Wardrobe\Core\Controllers\\';

Route::group(Config::get('core::routes.blog_group_rules'), function() use ($wardrobeControllers)
{
	Route::get('/', array('uses' => $wardrobeControllers.'HomeController@index', 'as' => 'wardrobe.index'));

	Route::get('post/{slug}', $wardrobeControllers.'PostController@getShow');
	Route::get('post/preview/{id}', $wardrobeControllers.'PostController@getPreview');
	Route::get('tag/{tag}', $wardrobeControllers.'PostController@getTag');
	Route::get('archive', $wardrobeControllers.'PostController@getIndex');
});
