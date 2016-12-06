<?php
	

	function dbquery($q) {
		$conn = pg_connect("dbname=tutoring user=dbuser password=dbpass");
		if (!$conn) {
			error_log("not connected");
			yield array("status" => "ERROR", "message" =>"Could not connect to backend.");
		}

		$result = pg_query($conn, $q);
		if (!$result) {
			pg_close($conn);
			yield array("status" => "ERROR", "message" => "Could not query backend.");
		}

		while ($row = pg_fetch_assoc($result)) {
			yield $row;
		}

		pg_close($conn);
		yield null;
	}

	if (isset($_GET['format'])) {
		header('Content-Type: application/json');
		$item = iterator_to_array(dbquery("SELECT * FROM gpio WHERE pin=18;"))[0];
		print json_encode(array("state" => intval($item['state'])));
		die();
	} else if (isset($_POST['state'])) {
		if($_POST['state'] == 'Lights on')
			$state = 1;
		else
			$state = 0;

		iterator_to_array(dbquery("UPDATE gpio SET state=" . $state . " WHERE pin=18;"));
	}
?>
<html>
	<head>
		<!--<link href='//fonts.googleapis.com/css?family=Raleway:400,300,600' rel='stylesheet' type='text/css'>-->
		<link rel="stylesheet" href="./resources/css/Skeleton-2.0.4/css/normalize.css">
		<link rel="stylesheet" href="./resources/css/font-awesome-4.7.0/css/font-awesome.min.css">
		<!--<link rel="stylesheet" href="//raw.githubusercontent.com/nathancahill/skeleton-fontawesome-buttons/master/skeleton-fontawesome-buttons.min.css">-->
		<link rel="stylesheet" href="./resources/css/Skeleton-2.0.4/css/skeleton.css">
		<link rel="stylesheet" href="./resources/css/balloon.css">

		<!--<link rel="stylesheet" href="./resources/css/core.css">-->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="./resources/js/js.cookie.js"></script>

		<meta name="viewport" content="width=device-width">

		<style type="text/css">
			body {
				background-color: #272822;
			}

			.off {
				margin-left: 50px;
				background-color: #FF0000 !important;
			}

			.on {
				background-color: #00FF00 !important;
			}

			.container {
				top: 50%;
				margin-top: -50px;
			}

		</style>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="three columns"><br></div>
				<div class="six columns" style="text-align: center;">
					<form method="POST" action="./gpio.php">
						<input class="on" type="submit" name="state" value="Lights on">
						<input class="off" type="submit" name="state" value="Lights off">
					</form>
				</div>
				<div class="three columns"><br></div>
			</div>
		</div>
	</body>
</html>
