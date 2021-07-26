<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8">
	<title>Nachweis für Tarifbeschäftigte</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body style="margin:10px">

<h1>Nachweis für Tarifbeschäftigte</h1>

<h2>Dateneingabe</h2>
<form action="uberstundennachweis.php" method="POST">
<div class="row">
	<div class="col-1">
		Mitarbeiter:
	</div>
	<div class="col-11">
		<input required type="text" name="name">
	</div>
</div>

<div class="row">
	<div class="col-1">
		Personalnummer:
	</div>
	<div class="col-11">
		<input required type="text" name="personalnummer">
	</div>
</div>

<div class="row">
	<div class="col-1">
		Monat:
	</div>
	<div class="col-2">
		<input required type="text" name="month">
	</div>


	<div class="col-1">
		Jahr:
	</div>
	<div class="col-8">
		<input required type="text" name="year">
	</div>
</div>

<input type="checkbox" name="ueberstunden"> Überstunden mit auswerten (werden dann vom Zeitkonto abgezogen)
<br><br>

<b>Dateneingabe</b><br>

<textarea name="csv" rows="25" cols="150" style="font-family:monospace"></textarea><br><br>

<input type="submit" value="PDF erzeugen">
</form>


<h2>Anleitung</h2>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>