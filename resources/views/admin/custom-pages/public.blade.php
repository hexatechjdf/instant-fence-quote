@if ($page->type == 'iframe')
    @extends('layouts.guest')
    @section('title', $page->slug ?? 'Page')
    @section('content')
        <iframe src="{{ $page->link }}" style="border:none;width:100%;height: 100vh; "></iframe>
    @endsection
@endif

@if ($page->type == 'page')
    {!! $page->description !!}
@endif
