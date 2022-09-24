@extends('home')

@section('title')
	Register
@endsection

@section('content')

<form action="/register" method="post">
	@csrf
	<div class="mb-3">
		<input autocomplete="off" autofocus class="form-control mx-auto w-auto" name="name" placeholder="username" type="text" value="{{old('name')}}">
		@error('name')
        <p class="text-danger">{{$message}}</p>
        @enderror
	</div>
	<div class="mb-3">
		<input class="form-control mx-auto w-auto" name="password" placeholder="Password" type="password">
		@error('password')
        <p class="text-danger">{{$message}}</p>
        @enderror
	</div>
	<div class="mb-3">
		<input class="form-control mx-auto w-auto"
		name="password_confirmation" placeholder="Confirm Password" type="password">
		@error('password_confirmation')
        <p class="text-danger">{{$message}}</p>
        @enderror
	</div>
	<button class="btn btn-primary" type="submit">Register</button>
</form>

@endsection