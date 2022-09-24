@extends('home')

@section('title')
	Search
@endsection

@section('content')
<h3 class="mb-3">Choose from {{$airportCount}} airports</h3>
<form action="/search" method="post">
	@csrf
	{{--origin--}}
	<div class="mb-3">
		<label for="originAirport" class="form-label">Type to search, then choose airports from list</label>
		<input list="originAirports" class="form-control mx-auto w-auto" name="origin" id="originAirport" placeholder="From" required>
		<datalist id="originAirports">
			@foreach ($airports as $airport)
			<option value="{{ $airport['iata'] }}">
			{{$airport['city_name']}}, {{$airport['country_code']}} - {{$airport['airport']}}
			</option>
			@endforeach
		</datalist>
		@error('origin')
        <p class="text-danger">{{$message}}</p>
        @enderror
	</div>
	{{--destination--}}
	<div class="mb-3">
		<input list="destinationAirports" class="form-control mx-auto w-auto" name="destination" id="destinationAirport" placeholder="To" required>
		<datalist id="destinationAirports">
			@foreach ($airports as $airport)
			<option value="{{ $airport['iata'] }}">
			{{$airport['city_name']}}, {{$airport['country_code']}} - {{$airport['airport']}}
			</option>
			@endforeach
		</datalist>
		@error('destination')
        <p class="text-danger">{{$message}}</p>
        @enderror
	</div>
	{{--departure date--}}
	<div class="mb-3">
		<label for="departureDate">Departure Date</label>
		<input class="form-control mx-auto w-auto" id="departureDate" name="departureDate" type="date" required>
		@error('departureDate')
        <p class="text-danger">{{$message}}</p>
        @enderror
	</div>
	<button class="btn btn-primary" type="submit">Search</button>
</form>
@endsection