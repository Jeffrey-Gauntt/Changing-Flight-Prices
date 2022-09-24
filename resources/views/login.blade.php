@extends('home')

@section('title')
	Login
@endsection

@section('content')
    <form action="/login" method="post">
		@csrf
        <div class="mb-3">
            <input autocomplete="off" autofocus class="form-control mx-auto w-auto" name="name" placeholder="Username" type="text" value="{{old('name')}}">
			@error('name')
        	<p class="text-danger">{{$message}}</p>
        	@enderror
        </div>
        <div class="mb-3">
            <input class="form-control mx-auto w-auto" name="password" placeholder="Password" type="password">
        </div>
        <button class="btn btn-primary" type="submit">Log In</button>
    </form>
@endsection