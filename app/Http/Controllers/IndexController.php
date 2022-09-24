<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    // show register form --- FUNCTION FOR TESTING PURPOSES
	public function TESTshow() {
		// // get contents from search
		// $flights = Storage::disk('local')->get('flights_2.json');

		// // decode JSON file into an object
		// $flights = json_decode($flights, false);

		// // make variable of each flight
		// $flights = $flights->getAirFlightDepartures->results->result->itinerary_data;


		// // loop through flights
		// foreach($flights as $flight) {
		// 	// pre-variables
		// 	// $itinerary_0 = $flights->itinerary_0;
		// 	$originAirportCode = $flight->slice_data->slice_0->departure->airport->code;
		// 	$originAirportName = $flight->slice_data->slice_0->departure->airport->name;
		// 	$destinationAirportCode = $flight->slice_data->slice_0->arrival->airport->code;
		// 	$destinationAirportName = $flight->slice_data->slice_0->arrival->airport->name;

        // 	// ready variables for insertion
		// 	$userId = auth()->id();
		// 	$origin = "(" . $originAirportCode . ") " . $originAirportName;
		// 	$destination = "(" . $destinationAirportCode . ") " . $destinationAirportName;
		// 	$departureDate = $flight->slice_data->slice_0->departure->datetime->date_display;
		// 	$departureTime = $flight->slice_data->slice_0->departure->datetime->time_12h;
		// 	$arrivalDate = $flight->slice_data->slice_0->arrival->datetime->date_display;
		// 	$arrivalTime = $flight->slice_data->slice_0->arrival->datetime->time_12h;
		// 	$connections = $flight->slice_data->slice_0->info->connection_count;
		// 	if($connections == 0) {
		// 		$connections = "Direct Flight";
		// 	}
		// 	$mainAirline = $flight->slice_data->slice_0->airline->name;
		// 	$airlineCount = $flight->slice_data->slice_0->airline->airline_count;
		// 	$tripDuration = $flight->slice_data->slice_0->info->duration;
		// 	$price = $flight->price_details->display_total_fare;

		// 	echo "<p>" . $price . "<p>";

			// insert into database
			// DB::table('flights')->insert([
			// 	'user_id' => $userId,
			// 	'origin' => $origin,
			// 	'destination' => $destination,
			// 	'departure_date' => $departureDate,
			// 	'departure_time' => $departureTime,
			// 	'arrival_date' => $arrivalDate,
			// 	'arrival_time' => $arrivalTime,
			// 	'number_of_connections' => $connections,
			// 	'main_airline' => $mainAirline,
			// 	'number_of_airlines' => $airlineCount,
			// 	'trip_duration' => $tripDuration,
			// 	'price' => $price
			// ]);
		}

    // show register form
	public function show() {
		// query database
		$flights = DB::table('flights')
			->orderBy('dateTime', 'desc')
			->paginate(10);

		// display results
		return view('main')
			->with('flights', $flights);
	}
}
