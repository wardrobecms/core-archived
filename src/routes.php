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
	Route::get('/', array('uses' => $wardrobeControllers.'AdminController@index', 'as' => 'wardrobe.admin.index'));
	Route::get('logout', array('uses' => $wardrobeControllers.'LoginController@destroy', 'as' => 'wardrobe.admin.logout'));
	Route::get('login', array('uses' => $wardrobeControllers.'LoginController@create', 'as' => 'wardrobe.admin.login'));
	Route::post('login', array('uses' => $wardrobeControllers.'LoginController@store'));
});

Route::group(Config::get('core::routes.api_group_rules'), function() use ($wardrobeControllers)
{
	Route::get('/', array('as' => 'wardrobe.api.home'));
	Route::resource('post', $wardrobeControllers.'Api\PostController');
	Route::resource('tag', $wardrobeControllers.'Api\TagController');
	Route::resource('user', $wardrobeControllers.'Api\UserController');
	Route::controller('dropzone', $wardrobeControllers.'Api\DropzoneController');
});
