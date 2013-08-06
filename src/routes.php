<?php

$wardrobeControllers = 'Wardrobe\Core\Controllers\\';

Route::group(Config::get('core::routes.blog_group_rules'), function() use ($wardrobeControllers)
{
	Route::get('/', array('uses' => $wardrobeControllers.'HomeController@index', 'as' => 'wardrobe.index'));

	Route::get('post/{slug}', array('uses' => $wardrobeControllers.'PostController@show', 'as' => 'wardrobe.posts.show'));
	Route::get('post/preview/{id}', array('uses' => $wardrobeControllers.'PostController@preview', 'as' => 'wardrobe.posts.preview'));
	Route::get('tag/{tag}', array('uses' => $wardrobeControllers.'PostController@tag', 'as' => 'wardrobe.posts.tags'));
	Route::get('archive', array('uses' => $wardrobeControllers.'PostController@index', 'as' => 'wardrobe.posts.archive'));
});

Route::group(Config::get('core::routes.admin_group_rules'), function() use ($wardrobeControllers)
{
	Route::get('/', $wardrobeControllers.'AdminController@getIndex');
	Route::get('logout', $wardrobeControllers.'LoginController@getLogout');
	Route::get('login', $wardrobeControllers.'LoginController');
});

Route::group(Config::get('core::routes.api_group_rules'), function() use ($wardrobeControllers)
{

});
