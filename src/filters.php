<?php

Route::filter('wardrobe.auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax()) return Response::make('Unauthorized', 401);

		return Redirect::guest(route('wardrobe.login', null, false));
	}
});
