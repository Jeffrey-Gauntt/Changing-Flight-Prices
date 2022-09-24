<div class="container text-center over-flow">
	<div class="row">
		<div class="col">
			<table class="table table-striped table-bordered align-middle">
				<thead>
					<tr>
						<th class="table-place">Origin</th>
						<th class="table-place">Destination</th>
						<th>Departure Date/Time</th>
						<th>Arrival Date/Time</th>
						<th># of Connections</th>
						<th>Main Airline</th>
						<th># of Airlines</th>
						<th>Trip Duration days<span class="duration">:</span>hrs<span class="duration">:</span>mins</th>
						<th>Price</th>
						<th># of days before departure price was checked</th>
						<th>Date/Time of Price Check</th>
						@auth
						<th></th>
						@endauth
					</tr>
				</thead>
				<tbody>
					@foreach ($flights as $flight)
						@php

						// departure variables
						$departure = date_create_from_format("Y-m-d H:i:s", $flight->departure_date . " " . $flight->departure_time);
						$departureDay = date_format($departure, "l");
						$departureDate = date_format($departure, "d-M-y");
						$departureTime = date_format($departure, "g:i a");

						// arrival variables
						$arrival = date_create_from_format("Y-m-d H:i:s", $flight->arrival_date . " " . $flight->arrival_time);
						$arrivalDay = date_format($arrival, "l");
						$arrivalDate = date_format($arrival, "d-M-y");
						$arrivalTime = date_format($arrival, "g:i a");

						// timestamp (dateTime) variables
						$timestamp = date_create_from_format("Y-m-d H:i:s", $flight->dateTime);
						$timestampDay = date_format($timestamp, "l");
						$timestampDate = date_format($timestamp, "d-M-y");
						$timestampTime = date_format($timestamp, "g:i a");

						@endphp
					<tr>
						<td>( {{ $flight->origin_airport_code }} )<br>{{ $flight->origin_airport_name }}<hr>{{ $flight->origin_city }},<br>{{ $flight->origin_country }}</td>
						<td>( {{ $flight->destination_airport_code }} )<br>{{ $flight->destination_airport_name }}<hr>{{ $flight->destination_city }},<br>{{ $flight->destination_country }}</td>
						<td>{{ $departureDay }}<br>{{ $departureDate }}<br>{{ $departureTime }}</td>
						<td>{{ $arrivalDay }}<br>{{ $arrivalDate }}<br>{{ $arrivalTime }}</td>
						<td>{{ $flight->number_of_connections }}</td>
						<td>{{ $flight->main_airline }}</td>
						<td>{{ $flight->number_of_airlines }}</td>
						<td>{{ $flight->trip_duration }}</td>
						<td><span class="dollarSign text-success">$</span>{{ number_format($flight->price) }}</td>
						<td>{{ $flight->date_diff }}</td>
						<td>{{ $timestampDay }}<br>{{ $timestampDate }}<br>{{ $timestampTime }}</td>
						@auth
						<td>
							<form action="/search" method="post">
								@csrf
								<input type="hidden" name="origin" value="{{$flight->origin_airport_code}}">
								<input type="hidden" name="destination" value="{{$flight->destination_airport_code}}">
								<input type="hidden" name="departureDate" value="{{ $flight->departure_date }}">
								<button class="btn btn-primary" type="submit">Check Price Again</button>
							</form>
						</td>
						@endauth
					</tr>
					@endforeach
				</tbody>
			</table>
			<div>
				{{ $flights->links() }}
			</div>
		</div>
	</div>
</div>