<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8">
	<title>Nachweis für Tarifbeschäftigte</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	
	<link rel="icon" href="favicon.png" type="image/x-icon">
  

</head>

<body style="margin:10px; background-color: #CCCCCC" >

<h1>Nachweis für Tarifbeschäftigte V 1.2</h1>

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
<br>
<input type="checkbox" name="fza"> Feiertag mit FZA
<br><br>

<b>Dateneingabe</b><br>

<textarea name="csv" rows="25" cols="150" style="font-family:monospace"></textarea><br><br>

<input type="submit" value="PDF erzeugen">
</form>
<br><br>

<h2>Anleitung</h2>

<p>Die eZeit in Browser aufrufen und zum Stundennachweis navigieren<br>
	<img src="img/ezeit_start.jpg" width="50%"></p>

<p>Im Stundennachweis mindestens eine zweite Seite laden. Die benötigte Exportoption steht nicht zur Verfügung, wenn nur eine Seite geladen ist.<br>
	<img src="img/ezeit2.jpg" width="50%"></p>

<p>Nun kann über den Export-Button<br>
	<img src="img/ezeit3.jpg" width="50%">
	<br>
	Der Export-Typ "Microsoft Excel (97-2003) Nur Daten" gewählt werden<br>
	<img src="img/ezeit4.jpg" width="50%">
</p>
<p>Die Datei wird nun entweder heruntergeladen und an einem Ort abgelegt, an dem man sie wiederfindet, oder alternativ direkt in Excel geöffnet.</p>

<p>Die Datei mit Micrsoft Excel öffnen (falls nicht schon im vorherigen Schritt getan) und über "Speichern unter" als "CSV (Trennzeichen-getrennt) (*.csv) abspeichern<br>
	<img src="img/ezeit_speichern.jpg" width="50%">
</p>

<p>Diese Datei nun mit dem Text-Editor öffnen um an den reinen Textinhalt zu kommen. Dazu einen Rechtsklick auf die Datei machen und über "Öffnen mit" und "Editor" die Datei im Texteditor öffnen<br>
	<img src="img/ezeit_oeffnen.jpg" width="50%">
</p>

<p>Den gesamten Text mit Strg + A markieren und kopieren (Strg + C) und hier auf der Seite oben im Textfeld einfügen. Dabei darauf achten, dass auch wirklich alles genau so übernommen wird, wie es in der Datei steht.<br>Zusätzlich noch die Felder mit Namen, Personalnummer und dem Monat und Jahr des ersten Blattes (siehe eZeit) ausfüllen. Letzteres ist nötig, da in der Datei kein Monat/Jahr mitgespeichert wird sondern lediglich der Tag, zur Berechnung um welchen Wochentag es sich handelt (um Samstags- und Sonntagszuschläge zu berechnen) ist es jedoch nötig auch den Monat und das Jahr zu kennen.</p>

<br><br>
<h2>Disclaimer</h2>

<p>Dieses Tool ist entstanden, da der Autor keine Lust mehr hatte, die Überstundennachweise händisch auszufüllen, wo die Zeiten doch bereits in der Zeiterfassung stehen und von dort automatisch an die Lohnbuchhaltung gehen könnten. Da dies aber derzeit nicht möglich ist, der Umweg über die automatische Generation der entsprechenden Formulare um so weniger Arbeit zu haben. Da es auch für andere Tarifbeschäftigte der FHH interessant sein könnte wird es hier kostenfrei zur Verfügung gestellt.</p>

<p style="color:#CC0000"><b>Dieses Tool wird ohne Gewährleistung zur Verfügung gestellt. Es obliegt dem Nutzer die erzeugten Nachweise zu kontrollieren. Der Autor dieses Tools übernimmt keine Verantwortung für evtl. unvollständig oder falsch erstellte Nachweise.</b></p>

<p> Update in Version 1.2:<br>
Es wurde die Berechnung der Feiertage für 2021 und 2022 eingefügt, sowie Heiligabend und Silvester. Dabei wurde auch die Berechnung der Nachtarbeit überarbeitet.</p>

<p>Derzeit fehlen werden folgende Felder <b>nicht</b> berechnet / ausgefüllt:
<ul>
	<li>Abgeltung durch Freizeit</li>
	<li>Überstunden aus Rufbereitschaft</li>
	<li>Überstunden im Dienstplan vorgesehen</li>
	<li>Teilzeitbeschäftigte Mehrstunden</li>
	<li>Bereitschaftsstunden</li>
	<li>Rufbereitschaftsstunden</li>
	<li>Rufbereitschaftspauschalen</li>
	<li>Wechselschichtarbeit stündlich</li>
	<li>Schichtarbeit stündlich</li>
	<li>Fehlstunden</li>
</ul>
</p>



<h2>Impressum</h2>
<p>Kai Thater, Iserbrooker Weg 67, 22589 Hamburg, dev@kai-thater.de</p>
<p>Fehler und Funktionswüsche bitte in den <a href="https://kai-thater.de/bug" target="_blanck">Bugtracker</a> eintragen oder <a href="https://github.com/Der-Kai-T/eZeit_Tools/issues" target="_blanck"> über GitHub</a> melden. Vielen Dank.</p>


<h2>Datenschutz</h2>
<p>Ich habe versucht, die Anwendung so Datensparsam wie möglich zu schreiben. Da die Erzeugung des PDFs aber Server-Seitig stattfindet, werden die Daten zwangsläufig dort benötigt. Die Datenübermittlung zum und vom Server erfolgt mittels Transportverschlüsselung (https). Die Server stehen in einem Rechenzentrum von Strato in Deutschland.</p>
<p>Die Stunden-Daten werden während der Übertragung temporär auf dem Server zwischengespeichert. Nach der Umwandlung der eingegebenen CSV-Daten in Daten, die das System verarbeiten kann, wird die übermittelte CSV-Datei mit einer leeren überschrieben. Es wird keine Verbindung zu den Mitarbeiter-Daten hergestellt. Diese Informationen werden auch nicht einzeln gespeichert. Mittels der leeren CSV-Dateien kann nachvollzogen werden, wie häufig das Tool genutz wurde. Der Dateiname der CSV-Dateien besteht aus dem MD5-Hash eines zufälligen, zehn bytes großen Hex-Dump gefolgt von dem aktuellen Unix-Timestamp. Damit soll sichergestellt werden, dass der Name eindeutig genug ist, dass mehr als eine Person das Tool zeitgleich nutzen kann und gleichzeitig kein Rückschluss auf die anfragende Person erzeugt werden kann. </p>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
