<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    // show user's search history page
	public function show() {

		// query database
		$flights = DB::table('flights')
			->where('user_id', auth()->id())
			->orderBy('dateTime', 'desc')
			->paginate(10);

		// get user's name
		$name = auth('web')->user('attributes')->name;

		return view('history')
			->with('flights', $flights)
			->with('name', $name);
	}
}
