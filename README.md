# Changing Flight Prices

### Video Demo: https://youtu.be/vre94yQK2Bo

### Live website: [Changing Flight Prices](https://flightprices.jeffreygauntt.com/)

### Description:

"Changing Flight Prices" is a web application that utilizes an API to retrieve live one-way airline ticket prices departing a particular date that are able to come back and check again later (hours later or days later) if the same flight is still the cheapest.

The same trip may be check several times to see if there is a differnence in price or airlines based on...

- time of day checked
- day of week checked
- number of days checked until trip
- number of connections
- airline
- total trip duration
- departure time
- arrival time
- or any combination of, etc.

***

## **User experience**

### Upon arriving

presented is a table of previously searched flights by all users. The table consists of the following information...

- **Origin** : trip origination location.
- **Destination** : trip destination location.
- **Departure Date/Time** : departure day, date and time at trip origination.
- **Arrival Date/Time** : arrival day, date and time at trip destination.
- **# of Connections** : the number of connecting flights that will be made during the trip, if trip is a direct flight, "Direct Flight" will be displayed.
- **Ticketing Airline** : many itineraries consist of multiple airlines working together, the "Ticketing Airline" is the main airline who the ticket will be purchased from.
- **# of Airlines** : number of different airlines that the itinerary consists of.
- **Trip Duration days:hrs:mins** : total trip duration from departure to arrival at destination including layovers/ connections displayed in format days:hours:minutes.
- **Price** : cost of trip.
- **# of days before departure price was checked** : number of days before trip the cost was checked.
- **Date/Time of Price Check** : day, date and time the trip was looked up.

When not logged in, the navigation bar at top of screen consists of 3 links...

- **Changing Flight Prices** : the title of site that directs back to home page.
- **Register** : directs to register page.
- **Login** : directs to login page.

Most recent searches are displayed first.

Pagination is at bottom of table, 10 results are shown per page.

If the user is on a phone or small screen, the table may be scrolled to easily see all the information and the "Register" and "Login" links are accessed by pressing the 3 lined menu icon located in the upper right corner of the screen.

At the bottom of screen is noted that the information data is provided by [Priceline.com](https://www.priceline.com/). When clicked [Priceline.com](https://www.priceline.com/) will open up in a different tab.

### Register page

A simple register page where user enters a username of their choice, a password and confirmation of password.

### Login page

A simple login page where user enters their username and password.

### Once registered or logged in

User is taken to site home page where at the top under navbar in center a message appears that says either...

- User created and logged in
- You are now logged in!

The navbar now no longer has ""Register" and "Login", but now consists of...

- **Changing Flight Prices** : (still the same as before) the title of site that directs back to home page.
- **New Search** : directs to new search page.
- **My Flight Search History** : directs to user's personal search history page.
- **Log Out** : logs user out and directs back to home page where at the top under navbar in center a message appears that says "You have been logged out!".

On the right end of each row of the table a button now appears that says "Check Price Again". This will perform another search of the trip origin, destination and departure date. The results can be looked at if the cheapest flight or flights have changed or remained the same. After search user directed to "My Flight Search History" page where most recent search is displayed first.

### New Search Page

Searches contain 3 user input parameters...

- origin
- destination
- departure date

Origin(From) and Destination(To): user types and airport or city and selects airport from results list.

Departure Date: user selects date from calender.

After search user directed to "My Flight Search History" page where most recent search is displayed first.

***

## **Tech**

### Overview

Changing Flight Prices is built with...

- Language of PHP
- utilizing the framework LARAVEL v9.23
- MySQL for a database
- Bootstrap 5 CSS framework
- custom CSS

### Routing

The web routes are registered and directed to the proper controller based on a get or post request. Laravel's middleware is utilized to protect a route if a user is properly logged in or not.

### Controllers

- IndexController : retireves all flights searched ordering by most recent search first with Laravel's pagination function specifying 10 per page, then directs to main view.

- UserController
	- "create" function returns the register form to user
	- "store" function register a new user. It receives user input...
		- name : checking an entry was made of at least 3 characters and now other user already has the same name.
		- password : checking an entry was made, that it matched the password confirmation and has at least 4 characters.
		- password is hashed using "bcrypt" method.
		- data is sent to Laravel's User::create class to insert into database
		- Laravel's auth() funtion is called to log user in
		- user redirected back to home page with a message that they are registered and logged in.
	- "login" function returns the login form to the user
	- "authenticate" function logs a user in
		- receives user input...
			- name : makes sure an entry was made and of at least 3 characters.
			- password : makes sure entry was made and of at least 4 characters.
		- attempts to log in in using Laravel's attempt method.
			- if successful user redirected to home page.
			- if failed, error message is returned to user that their username and/or password is incorrect.
			- specification is not given weither it was the username, password or both that was incorrect. This is done so someone can not phish around to see if a certain username exists of the system.

- SearchController
	- "show" function shows search form to user
		- the list of airports is was retrieved from the API and stored in a JSON file. Doing this reduces the number of API calls by almost half, therfore reducing costs.
		- airport list is retrived, decoded from JSON format to an array
		- JSON document is narrowed down to the array of airports to a variable.
		- number of airports in array is counted
			- doing this allows airports to be removed or added to list with an exact count of number of airports to be performed and displayed to user.
		- user is redirected to search form.
	- "search" function searches the user's request
		- inputs received
			- origin : checks that an entry was made of exactly 3 characters.
			- destination : checks that an entry was made of exactly 3 characters.
			- departureDate : checks that an entry was made in the proper format.
		- airports list is retrived from JSON file.
		- JSON document is narrowed down to the array of airports to a variable.
		- array of airports is looped through, airports codes are pushed onto a new array.
		- user's input of origin and destination is made sure to be an uppercase string (this is necessary for the API call).
		- user's input is check for being a valid airport against array of airports from JSON file.
		- user's input date is checked that is at least one day in future, as past flights cannot be looked up.
		- API call is made
			- Changing Flight Prices utilizes a service provider "RapidAPI". For use of this API, 500 free API calls are allowed per month.
			- API call is made using the cURL method.
			- user's input is inserted into proper place to make API call.
			- response is received with error handling of API connection.
		- JSON response is decoded into an array
		- checking for any other type of errors
			- if present user is displayed proper message from API response.
		- response array is narrowed down into a variable of itineraries.
		- lowest price variable of $1 million dollars is declared.
		- cheapest itineraries variable is initiated.
		- each itinerary is looped through to find the cheapest and pushed onto cheapest itineraries array.
			- if more than one itinerary has the same price, all will be pushed onto array.
		- cheapest itineraries are looped through and inserted into flights table.
			- other fields are also created...
				- user id
				- date difference : calcualtion of number of days from trip departure to day being searched.
		- user redirected to their search history page.

- HistoryController
	- searches belonging to the user logged in are retrieved from database and paginated 10 per page.
	- user's name is retrieved.
	- user is redirected to history page.

### Views

- home : this is the main layout consisting of page...
	- head
	- navbar
	- elements with directives for...
		- only showing certain elements is a user is logged in or not
		- messages
		- content that other views extend to
	- footer
		- with link and credit priceline.com for data

- main : includes purpose to site to user.

- register : consisting of register form.

- login : consisting of login form.

- history : consisting of table of searches

- search : search form

- errorSearch : displays error to user is search failed or returned no results.

- table : table for displaying searched flights.

- bootstrap-5 : pagination display

### CSS Design

Changing Flight Prices uses Bootstrap 5. Minimal custom CSS was needed...

- dollar sign of table
- make table scroll on smaller screens
- widen origin and destination columns on table
- space out colons on duration column head of table

### Database

Laravel's migrations where used to create the tables mySQL database.

***

## **Conclusion**

Changing Flight Prices is a tool for curious get-a-way travelers to see how flight prices change according to when they are looked up, thus helping to find better deals.