@extends('core::admin.layout')

@section('title')
	Admin
@stop

@section('content')
	<div id="main-region"></div>
@stop

@section('footer.js')
	<script type="text/javascript">
		$(document).ready(function() {
			Wardrobe.start({
				user: {{ $user }},
				users: {{ $users }},
				api_url: "{{ route('wardrobe.api.index') }}",
				admin_url: "{{ route('wardrobe.admin.index') }}",
				blog_url: "{{ route('wardrobe.index') }}",
			});
		});
		window.Lang = {@foreach($locale as $key => $item) {{ $key }}: "{{ $item }}", @endforeach}
	</script>
@stop
