@extends('home')

@section('title')
	Home
@endsection

@section('content')

<h3 class="mb-3">Seach one-way flights to find the cheapest,</h3>
<h3 class="mb-3">and check another day or time to see if cheapest flight has changed</h3>
<h3 class="mb-3">for that date.</h3>

@include('includes/table')

@endsection