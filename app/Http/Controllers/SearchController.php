<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
	// show search form
	public function show() {
		// get airports from JSON file
		$airports = Storage::disk('local')->get('airports.json');
		// decode JSON file into array
		$airports = json_decode($airports, true);
		// narrow down to airport list array
		$airports = $airports['airports'];
		// how many airports
		$airportCount = count($airports);
		
		return view('search')
		->with('airports', $airports)
		->with('airportCount', $airportCount);
	}
	
    // process search
	public function search(Request $request) {

		// validate inputs
		$formFields = $request->validate([
			'origin' => ['required', 'size:3'],
            'destination' => ['required', 'size:3'],
            'departureDate' => ['required', 'date_format:Y-m-d']
        ]);
		
		// get airports from JSON file
		$airports = Storage::disk('local')->get('airports.json');
		// decode JSON file into array
		$airports = json_decode($airports, true);
		// narrow down to airport list array
		$airports = $airports['airports'];
		// array of all airport codes
		$airportCodes = array();
		foreach($airports as $airport){
			array_push($airportCodes, $airport['iata']);
		}

		// origin and destination airport codes to uppercase
		$origin = strtoupper($formFields['origin']);
		$destination = strtoupper($formFields['destination']);

		// vallidate airport code input
		if (!in_array($origin, $airportCodes) || !in_array($destination, $airportCodes)) {
			return view('errorSearch')
				->with('message', "Something not right about your origin or destination");
		}

		// validate date input is after today
		if (strtotime($formFields['departureDate']) <= strtotime(date("Y-m-d"))) {
			return view('errorSearch')
				->with('message', "Date must be tommorrow or later");
		}

        // make API call to RapidAPI
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://priceline-com-provider.p.rapidapi.com/v2/flight/departures?sid=iSiX639&departure_date=" . $formFields['departureDate'] . "&adults=1&origin_airport_code=" . $formFields['origin'] . "&destination_airport_code=" . $formFields['destination'],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"X-RapidAPI-Host: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
				"X-RapidAPI-Key: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
			],
		]);

		$itineraries = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			echo "cURL Error #:" . $err;
		}

		// decode JSON file into array
		$itineraries = json_decode($itineraries, true);

		// handling other errors returned by API
		if (array_key_first($itineraries['getAirFlightDepartures']) == "error") {
			$message = $itineraries['getAirFlightDepartures']['error']['status'];
			return view('errorSearch')
				->with('message', $message);
		}

		// narrow down to individual itineraries
		$itineraries = $itineraries['getAirFlightDepartures']['results']['result']['itinerary_data'];
		
		// initiate lowest price variable to 1 million
		$lowestPrice = 1000000;
		
		// initiate cheapest itineraries array variable
		$cheapestItineraries;
		
		// loop through data to find cheapest itineraries
		foreach($itineraries as $itinerary){
	
			$itineraryPrice = intval($itinerary['price_details']['display_total_fare']);
			
			if ($itineraryPrice < $lowestPrice) {
				$lowestPrice = $itineraryPrice;
				$cheapestItineraries = [$itinerary];
			} elseif ($itineraryPrice == $lowestPrice) {
				array_push($cheapestItineraries, $itinerary);
			}	
		}


		// loop through cheapest itineraries and insert into database
		foreach($cheapestItineraries as $itinerary) {
			// pre-variables
			$today = date_create(date("Y-m-d"));
			
			
			// ready variables for insertion
			$userId = auth()->id();
				// origin
			$originAirportCode = $itinerary['slice_data']['slice_0']['departure']['airport']['code'];
			$originAirportName = $itinerary['slice_data']['slice_0']['departure']['airport']['name'];
			$originCity = $itinerary['slice_data']['slice_0']['departure']['airport']['city'];
			$originCountry = $itinerary['slice_data']['slice_0']['departure']['airport']['country'];
					// date_created for date_diff() calculation
			$departureDate = date_create($itinerary['slice_data']['slice_0']['departure']['datetime']['date']);
			$departureTime = $itinerary['slice_data']['slice_0']['departure']['datetime']['time_24h'];
				// destination
			$destinationAirportCode = $itinerary['slice_data']['slice_0']['arrival']['airport']['code'];
			$destinationAirportName = $itinerary['slice_data']['slice_0']['arrival']['airport']['name'];
			$destinationCity = $itinerary['slice_data']['slice_0']['arrival']['airport']['city'];
			$destinationCountry = $itinerary['slice_data']['slice_0']['arrival']['airport']['country'];
			$arrivalDate = $itinerary['slice_data']['slice_0']['arrival']['datetime']['date'];
			$arrivalTime = $itinerary['slice_data']['slice_0']['arrival']['datetime']['time_24h'];
				// misc info
			$connections = $itinerary['slice_data']['slice_0']['info']['connection_count'];
			if($connections == 0) {
				$connections = "Direct Flight";
			}
			$mainAirline = $itinerary['slice_data']['slice_0']['airline']['name'];
			$airlineCount = $itinerary['slice_data']['slice_0']['airline']['airline_count'];
			$tripDuration = $itinerary['slice_data']['slice_0']['info']['duration'];
			$price = $itinerary['price_details']['display_total_fare'];
			$dateDiff = date_diff($today, $departureDate)->days;
			// print("<pre>".print_r($itinerary,true)."</pre>");
			
			// insert into database
			DB::table('flights')->insert([
				'user_id' => $userId,
				'origin_airport_code' => $originAirportCode,
				'origin_airport_name' => $originAirportName,
				'origin_city' => $originCity,
				'origin_country' => $originCountry,
				'departure_date' => $departureDate,
				'departure_time' => $departureTime,
				'destination_airport_code' => $destinationAirportCode,
				'destination_airport_name' => $destinationAirportName,
				'destination_city' => $destinationCity,
				'destination_country' => $destinationCountry,
				'arrival_date' => $arrivalDate,
				'arrival_time' => $arrivalTime,
				'number_of_connections' => $connections,
				'main_airline' => $mainAirline,
				'number_of_airlines' => $airlineCount,
				'trip_duration' => $tripDuration,
				'price' => $price,
				'date_diff' => $dateDiff
			]);
		}

		return redirect('/history');
	}
}