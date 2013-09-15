<?php

Route::filter('wardrobe.auth', function()
{
	$auth = Wardrobe::getWardrobeAuth();

	if ($auth->guest())
	{
		if (Request::ajax()) return Response::make('Unauthorized', 401);

		return Redirect::guest(URL::route('wardrobe.admin.login'));
	}
});
