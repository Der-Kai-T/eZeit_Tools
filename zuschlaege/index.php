<form action="uberstundennachweis.php" method="POST">
Mitarbeiter: <input required type="text" name="name"><br>
<br>

Personalnummer: <input required type="text" name="personalnummer"><br>
<br>

Monat: <input required type="text" name="month"><br>
<br>
Jahr: <input required type="text" name="year"><br><br>

<input type="checkbox" name="ueberstunden">Überstunden mit auswerten (werden dann vom Zeitkonto abgezogen)
<br><br>

<b>CSV aus Excel</b><br>

<textarea name="csv" rows="45" cols="150" style="font-family:monospace"></textarea><br><br>

<input type="submit" value="PDF erzeugen">
</form>