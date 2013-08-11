<?php

Route::filter('wardrobe.auth', function()
{
	Config::set('auth.table', 'Wardrobe/Models/User');

	$auth = Auth::createEloquentDriver();
	if ($auth->guest())
	{
		if (Request::ajax()) return Response::make('Unauthorized', 401);

		return Redirect::guest(route('wardrobe.admin.login', null, false));
	}
});
