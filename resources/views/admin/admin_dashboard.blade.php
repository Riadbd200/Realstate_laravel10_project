<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
	<meta name="author" content="NobleUI">
	<meta name="keywords" content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<title>RealState Dashboard</title>

   @include('admin.inc.styles')
   
</head>
<body>
	<div class="main-wrapper">

		<!-- partial:partials/_sidebar.html -->
	    @include('admin.inc.sidebar')
  
		<!-- partial -->
	
		<div class="page-wrapper">
					
			<!-- partial:partials/_navbar.html -->
		 @include('admin.inc.navbar')
			<!-- partial -->

	      @yield('admin')

			<!-- partial:partials/_footer.html -->
			@include('admin.inc.footer')
			<!-- partial -->
		
		</div>
	</div>

  @include('admin.inc.scripts')

</body>
</html>    