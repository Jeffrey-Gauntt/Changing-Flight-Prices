@extends('home')

@section('title')
	My History
@endsection

@section('content')
<h1>Search History of {{ $name }}</h1>

@include('includes/table')

@endsection