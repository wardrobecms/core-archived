@extends(theme_view('layout'))

@section('title')
  Archives
@stop

@section('content')
  <section>
    <h2 class="title">Example of All Tags</h2>

    @foreach (Wardrobe::posts() as $item)
      {{ $item['title']}}
    @endforeach

    @foreach (Wardrobe::tags() as $item)
      @if ($item['tag'] != "")
        <li><a href="{{ wardrobe_url('/tag/'.$item['tag']) }}">{{ $item['tag'] }}</a></li>
      @endif
    @endforeach

  </section>
@stop
