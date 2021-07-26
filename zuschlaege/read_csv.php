<html>
	<head>
		<title>
			Read csv
		</title>
	</head>
	<body style="background-color: #111111; color:white">

<table style="border: 1px solid white" border="1">
	<thead>
		<tr>
			<th>Datum</th>
			<th>Wochentag</th>
			<th>Kommen</th>
			<th>Gehen </th>
			<th>Anwesenheit</th>
			<th>Pause</th>
			<th>Arbeitszeit</th>
			<th>Sonntagsarbeit</th>
			<th>Nachtarbeit<br>21-24 / 4-6</th>
			<th>Nachtarbeit<br>0 - 4</th>
			<th>Samstagsarbeit 13 - 21</th>
			<th>Überstunden</th>
			<th>Abgeltung durch Freizeit</th>
			<th>ÜS aus RB</th>
			<th>ÜS im DP</th>
			<th>TZ Mehr</th>
			<th>Feiertag ohne FZA</th>
			<th>Feiertag mit FZA</th>
			<th>24.12. 6-14</th>
			<th>24.12. ab 14 </th>
			<th>31.12. 6-14 </th>
			<th>31.12. ab 14</th>
			<th>Bereitschaft</th>
			<th>Rufbereitschaft</th>
			<th>Rufber.Pausch. Mo-Fr</th>
			<th>Rufber.Pausch. Sa-So</th>
			<th>Wechselschichtarb stünd</th>
			<th>Schichtarbeit</th>
			<th>Fehlstunden</th>
		</tr>
	</thead>
	<tbody>
<?php
	
	$complete_data = array();
	
	ini_set('auto_detect_line_endings',TRUE);

	$now = time();
	$rand = md5(bin2hex(random_bytes(10)).$now);
	$filename = "tmp/$rand.txt";
	file_put_contents($filename, $_POST['csv']);


	$file = fopen($filename, "r");

	$start_month = $_POST['month'];
	$month = $_POST['month'];
	$start_year = $_POST['year'];
	$year = $_POST['year'];

	$prev_day = 0;
	$prev_date_export = 0;
	
	

	while(($data = fgetcsv($file, 0, ";", '"')) !== FALSE){
	
		$start_mins = 0;
		$end_mins = 0;
		$anwesend_mins = 0;
		$pause_min = 0;
		$arbeitszeit_mins = 0;
		$sonntagsarbeit_mins = 0;
		$nachtarbeit1_mins = 0;
		$nachtarbeit_vor4_mins = 0;
		$samstagsarbeit_mins = 0;
		$ueberstunden_mins = 0;

		try{
			if(isset($data[1])){
				
				$date = substr($data[1], 0,-1);
				
				if(!is_numeric($date)){
					continue;
				}else{
					$date = $date *1;
				}
			}else{
				continue;
			}
		}catch(Exception $ex){
			print_r($ex);
		}


		//combine month and day (and current year)

		if($prev_day > $date){
			$month = $month -1;
		}
		$prev_day = $date;

		if($month <= 0){
			$year = $year -1;
			$month = 12;
		}


		$date_full = sprintf('%02d', $date).".".sprintf('%02d', $month).".".sprintf('%04d', $year);
		

		$weekday_number = UnixToDayNumber(TimeToUnix($date_full));
		$weekday = DayNumberToDayName($weekday_number);



	//	print_r($data);
		
		//check and sanitize start and end-time
		//start-time is in field 10
		//end-time is in field 11

		

	

			$start_exploded = explode("\n", $data[10]);
			$end_exploded = explode("\n", $data[11]);

			$count_start = count($start_exploded);
			$count_end = count($end_exploded);

			if($count_end == $count_start){
				for($i = 0; $i < $count_start ; $i++){

					$start= "";
					$end = "";
					$anwesend = "";

					if(isset($start_exploded[$i])){
						$start = $start_exploded[$i];
		
						$start_first = substr($start, 0,1);
		
						//remove booking-type identifier from start-time
						if($start_first == "!" || $start_first == "*" || $start_first == "." || $start_first == " "){
							$start = substr($start, 1);
						}
		
						//remove everything else that isn't part of the time
						$start = substr($start, 0, 5);
					}
		
					//do the same for the end-time
					if(isset($end_exploded[$i])){
						$end = $end_exploded[$i];
		
						$end_first = substr($end, 0,1);
		
						//remove booking-type identifier from end-time
						if($end_first == "!" || $end_first == "*" || $end_first == "." || $end_first == " "){
							$end = substr($end, 1);
						}
		
						//remove everything else that isn't part of the time
						$end = substr($end, 0, 5);
					}
		

					if($end != ""){
						$start_ = explode(":", $start);
						$end_   = explode(":", $end);

						if(isset($start_[1])){
							$start_mins = $start_[0]*60+$start_[1];
						}

						if(isset($end_[1])){
							$end_mins = $end_[0]*60+$end_[1];
						}
					
						if(isset($start_mins) && isset($end_mins)){
							$anwesend_mins = $end_mins - $start_mins;


							if($anwesend_mins < 6*60){
								$pause_min = 0;
							}else if ($anwesend_mins < 9*60){
								$pause_min = 30;
							}else{
								$pause_min = 45;
							}
							
						
	
							$arbeitszeit_mins = $anwesend_mins - $pause_min;

					




							//sonntagsarbeit
							if($weekday_number == 0){
								$sonntagsarbeit_mins = $arbeitszeit_mins;
							}else{
							
								$sonntagsarbeit_mins = 0;
							}

							//Samstagsarbeit 

							if($weekday_number == 6){
								$samstagsarbeit_ende = 0;
								$samstagsarbeit_beginn = 0;
							
	
								
									if($start_mins < 13*60 && $end_mins > 13*60){
										$samstagsarbeit_beginn = 13*60;
									
									}else if($start_mins >= 13*60 && $start_mins <= 21*60){
										$samstagsarbeit_beginn = $start_mins;
									
									}
	
									if($end_mins < 21*60 && $start_mins < 21*60 && $end_mins > 13*60){
										$samstagsarbeit_ende = $end_mins;
									}else if($start_mins < 21*60 && $end_mins > 21*60){
										$samstagsarbeit_ende = 21*60;
									}
	
					
	
								$samstagsarbeit_mins = $samstagsarbeit_ende - $samstagsarbeit_beginn;


							}else{
								$samstagsarbeit = "";
							}

							//Nachtarbeit 0-4

							if($start_mins < 4*60 && $end_mins < 4*60){
								$nachtarbeit_vor4_mins = $end_mins - $start_mins;
							}else if($start_mins < 4*60 && $end_mins > 4*60){
								$nachtarbeit_vor4_mins = 4*60 - $start_mins;
							}else{
								$nachtarbeit_vor4_mins = 0;
							}

							

					

							//Nachtarbeit nach 21 uhr
							$nachtarbeit_nach21_mins = 0;

							
							if($start_mins <= 21*60 && $end_mins < 24*60 && $end_mins > 21*60){
								$nachtarbeit_nach21_mins = $end_mins - 21*60;
							}else if($start_mins > 21*60 && $end_mins < 24*60 && $end_mins > 21*60){
								$nachtarbeit_nach21_mins = 4*60 - $start_mins;
							}

							
							//Nachtarbeit nach 4 aber vor 6

							$nachtarbeit_ende = 0;
							$nachtarbeit_beginn = 0;
						

							if($end_mins > 4*60){
								if($start_mins < 4*60 && $end_mins > 4*60){
									$nachtarbeit_beginn = 4*60;
								}else if($start_mins > 4*60 && $start_mins < 6*60){
									$nachtarbeit_beginn = $start_mins;
									
								}

							
								if($end_mins < 6*60 && $start_mins < 6*60){
									$nachtarbeit_ende = $end_mins;
								}else if($start_mins < 6*60 && $end_mins > 6*60){
									$nachtarbeit_ende = 6*60;
								}

							}

							$nachtarbeit_vor6_mins = $nachtarbeit_ende - $nachtarbeit_beginn;
						

							$nachtarbeit1_mins = $nachtarbeit_nach21_mins + $nachtarbeit_vor6_mins;

							



							//Überstunden

							if($weekday_number > 0 && $weekday_number < 6){
								$sollstunden_mins = 7*60+48;
							}else{
								$sollstunden_mins = 0;
							}


							$ueberstunden_mins = $arbeitszeit_mins - $sollstunden_mins;






						}

						







					}


						//sonntagsarbeit
						if($weekday_number == 0){
							$entry['sonntag'] = 1;
						}else{
							$entry['sonntag'] = 0;
						}

					//create array to push

					$entry['date_ts'] 			= TimeToUnix($date_full);
					$entry['kommen']			= $start_mins;
					$entry['gehen']				= $end_mins;
					$entry['anwesenheit']		= $anwesend_mins;
					$entry['pause']				= $pause_min;
					$entry['arbeitszeit']		= $arbeitszeit_mins;
					$entry['sonntagsarbeit']	= $sonntagsarbeit_mins;
					$entry['nacht1']			= $nachtarbeit1_mins;
					$entry['nacht2']			= $nachtarbeit_vor4_mins;
					$entry['samstag']			= $samstagsarbeit_mins;
					$entry['ueberstunden']		= $ueberstunden_mins;
					
				

					if($prev_date_export == $date_full){
						$last_entry = array_pop($complete_data);

						
						$entry['anwesenheit']		+= $last_entry['anwesenheit'];
						$entry['pause']				+= $last_entry['pause'];
						$entry['arbeitszeit']		+= $last_entry['arbeitszeit'];
						$entry['sonntagsarbeit']	+= $last_entry['sonntagsarbeit'];
						$entry['nacht1']			+= $last_entry['nacht1'];
						$entry['nacht2']			+= $last_entry['nacht2'];
						$entry['samstag']			+= $last_entry['samstag'];
						$entry['ueberstunden']		+= $last_entry['ueberstunden'];
					}
					
					
					array_push($complete_data, $entry);
					

					$prev_date_export = $date_full;
				}

			}
		// 

	}


	foreach($complete_data as $entry){
		
		if($entry['sonntag'] == 1){
			echo "<tr style='background-color: #550000'>";
		}else{
			echo "<tr>";
		}

		if($entry['ueberstunden'] <0){
			$entry['ueberstunden'] = 0;
		}

		echo"<td>".UnixToDate($entry['date_ts'])."</td>\n";
		echo"<td>".DayNumberToDayName(UnixToDayNumber($entry['date_ts']))."</td>\n";
		echo"<td>".format_time($entry['kommen'])."</td>\n";
		echo"<td>".format_time($entry['gehen'])."</td>\n";
		echo"<td>".format_time($entry['anwesenheit'])."</td>\n";
		echo"<td>".format_time($entry['pause'])."</td>\n";
		echo"<td>".format_time($entry['arbeitszeit'])."</td>\n";
		echo"<td>".format_time($entry['sonntagsarbeit'])."</td>\n";
		echo"<td>".format_time($entry['nacht1'])."</td>\n";
		echo"<td>".format_time($entry['nacht2'])."</td>\n";
		echo"<td>".format_time($entry['samstag'])."</td>\n";
		echo"<td>".format_time($entry['ueberstunden'])."</td>\n";
		

		echo"</tr>";

	}

	
	echo count($complete_data). " Zeilen";

	
	file_put_contents($filename, "");
	


?>
</tbody>
</table>

</body>
</html>

<?php
	function DayNumberToDayName($input){
		$tag[0] = "Sonntag";
		$tag[1] = "Montag";
		$tag[2] = "Dienstag";
		$tag[3] = "Mittwoch";
		$tag[4] = "Donnerstag";
		$tag[5] = "Freitag";
		$tag[6] = "Samstag";

		return $tag[$input];
	}

	function UnixToDayNumber($input){
		setlocale(LC_ALL, "de_DE.utf8");
		$daynumber  = date("w", $input);
		return $daynumber;
	}

	function TimeToUnix($input){
		@list ($date, $time)      = explode(' ', $input, 2);
		@list ($day, $mon, $year) = explode('.', $date);   
		
		$timestamp = strtotime("$year-$mon-$day $time");
		
		return $timestamp;
	}

	function UnixToDate($input){
		$datum  = date("d.m.Y", $input);
			
		$timestring = "$datum";
				
		return $timestring;
	}	

	function format_time($minutes){
		$return_time = "";
		if($minutes == 0){
			$return_time = "";
		}else{

			$hours 					= floor($minutes/60);
			$remaining_minutes 		= $minutes - ($hours*60);

			$hours					= sprintf('%02d', $hours);
			$remaining_minutes		= sprintf('%02d', $remaining_minutes);
			$return_time = $hours . ":" . $remaining_minutes;
		}

		return $return_time;
	}
?>