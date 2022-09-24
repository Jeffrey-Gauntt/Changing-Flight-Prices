@extends('home')

@section('title')
	Error - Search
@endsection

@section('content')
<h3 class="mb-3">{{$message}}</h3>
<a href="/search"><button class="btn btn-primary" type="button">Try Search Again</button></a>
@endsection