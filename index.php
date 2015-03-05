<?php
?>
<html ng-app="myApp">
	<head>
	    <title>Simple News Feed</title>
	     <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    
	   	<link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    	<script src="css/bootstrap/js/bootstrap.min.js"></script>

	    <link rel="stylesheet" type="text/css" href="css/style.css">
	    <script src="js/js/main.js"></script>
	    <link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	</head>

	<body>
		<div id="wrapper">
			<div class="page-header col-xs-12" id="naslov">					
						<h3>Simple NewsFeed</h3>								
			</div>
		</div>


		<div ng-view></div>
		
	</body>

	<script src="js/lib/angular/angular.js"></script>
	<script src="js/lib/angular-resource/angular-resource.js"></script>
	<script src="js/lib/angular-route/angular-route.js"></script>
	<script src="js/lib/angular-sanitize/angular-sanitize.js"></script>

	<script src="js/app/app.js"></script>
	<script src="js/app/controllers/controllers.js"></script>
	<script src="js/app/services/blog_services.js"></script>
	<script src="js/app/services/rest_services.js"></script>


</html>