<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, width=device-width">

        <!-- http://getbootstrap.com/docs/5.1/ -->
        <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" rel="stylesheet">
        <script crossorigin="anonymous" src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"></script>

        <link href="https://img.icons8.com/external-kiranshastry-gradient-kiranshastry/64/000000/external-flight-interface-kiranshastry-gradient-kiranshastry-1.png" rel="icon">

        <link href="css.css" rel="stylesheet">

        <title>Flight Prices: @yield('title')</title>

    </head>

    <body>

		<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
			<div class="container-fluid">
				<a class="navbar-brand" href="/">Changing Flight Prices</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					@auth
					<ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link" href="/search">New Search</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/history">My Flight Search History</a>
						</li>
					</ul>
					<form action="logout" method="post" class="nav-link">
						@csrf
						<input type="submit" value="Log Out">
					</form>
					@else
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link" href="/register">Register</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/login">Login</a>
						</li>
					</ul>
					@endauth
				</div>
			</div>
		</nav>

		@if(session()->has('message'))
		  <p class="text-center">
		    {{session('message')}}
		  </p>
		@endif

        <main class="container-fluid py-5 text-center">
            @yield('content')
        </main>

        <footer class="mb-5 small text-center text-muted">
            Data provided by <a target="_blank" href="https://priceline.com/">Priceline.com</a>
        </footer>

    </body>

</html>
