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

	function UnixToMonth($input){
		$datum  = date("m", $input);
			
		
				
		return $datum;
	}	

	function UnixToDay($input){
		$datum  = date("d", $input);
			
		
				
		return $datum;
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