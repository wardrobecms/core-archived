@extends('core::admin.layout')

@section('title')
	{{ Lang::get('core::wardrobe.login') }}
@stop

@section('content')
	<div id="login-region">
		<h1>{{ Lang::get('core::wardrobe.login') }}</h1>
		@if (Session::has('login_errors'))
		<div class="alert alert-block alert-error">
			<p>
				{{ Lang::get('core::wardrobe.login_incorrect')}} <a href="{{ url('wardrobe/login/remind') }}">{{ Lang::get('core::wardrobe.login_forgot') }}</a>
			</p>
		</div>
		@endif
		<form method="post" action="{{ route('wardrobe.admin.login') }}" class="form-horizontal">
			<p>
				<input type="text" id="inputEmail" name="email" placeholder="{{ Lang::get('core::wardrobe.login_email') }}" value="{{ Input::old('email') }}">
			</p>
			<p>
				<input type="password" id="inputPassword" name="password" placeholder="{{ Lang::get('core::wardrobe.login_password') }}">
			</p>
			<p>
				<label class="checkbox"><input type="checkbox" name="remember"> {{ Lang::get('core::wardrobe.login_remember') }}</label>
			</p>
			<button type="submit" class="btn">{{ Lang::get('core::wardrobe.login_sign_in') }}</button>
		</form>
	</div>
@stop

@section('footer.js')

@stop
