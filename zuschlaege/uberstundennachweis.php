<?php

include("functions.php");
/*******************************************************************
							To-do-Liste
*******************************************************************/		

#     	TO DO




# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

# 		Nice to have




# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

#  		DONE

/*******************************************************************
							Daten
*******************************************************************/
$name = $_POST['name'];

$personalnummer = $_POST['personalnummer'];

$monat = sprintf('%02d', $_POST['month']);
$jahr = sprintf('%04d', $_POST['year']);
	
/*******************************************************************
							Hier geht es dann los
*******************************************************************/		
	
	$pdf_filename = "Ueberstunden_nachweis.pdf";
	
	
/*******************************************************************
							Lade die Daten
*******************************************************************/	

	$complete_data = array();

	$now = time();
	$rand = md5(bin2hex(random_bytes(10)).$now);
	$filename = "tmp/$rand.txt";
	file_put_contents($filename, $_POST['csv']);


	$file = fopen($filename, "r");


	
	ini_set('auto_detect_line_endings',TRUE);
	

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


	file_put_contents($filename, "");

/*******************************************************************
							Wandle die Daten
*******************************************************************/	
	


		
/*******************************************************************
							Statische Texte
							keine Einrückungen für die Texte!!
*******************************************************************/	



/*******************************************************************
							Erzeuge das PDF
*******************************************************************/	
	
use setasign\Fpdi\Fpdi;
require_once('fpdf/fpdf.php');
require_once('fpdi2/src/autoload.php');






$pdf=new Fpdi('P', 'mm', 'A4');


//$pdf->SetAutoPageBreak(false);



#*******************************************************
#           Zeichne Rahmen
#*******************************************************

# x-achse links rechts
# y-achse hoch runter

#Line (start_x, start_y, ende_x, ende_y)


/*

Rot: 	225,	0,	25
DBlau:	0,	48,	99
Blau	0, 92, 169
gr		227, 227, 227


# Image('Link', Pos von Links, Pos von oben, Breite)
			$pdf->Image('img/misc/fpdf_Logo_4c_300.jpg', 220, 19.59, 60);

			Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, boolean fill [, mixed link]]]]]]])
*/




$page_width			= 210;
$page_height		= 297;
$mm_per_pt			= 0.352778;
$pt_per_mm			= 2.83465;

$margin				= 10;
$margin_left		= 25;
$margin_right		= 10;
$margin_top			= 10;//13.2;
$margin_bottom		= 17.6;

$font_size			= 13;
$font_size_small	= 10;
$font_size_smaller	= 8;

$offset_line = 6.3;
$start_line = 59;

$offset_column = 7.85;
$start_column = 24.5;

$month_sums = array();

for($i = 0; $i < 22; $i++){
	$month_sums[$i] = 0;
}




$pdf->SetFont('Helvetica', '', $font_size);
$pdf->SetTextColor(0,0,0);
$pdf->setFillColor(255,255,255);


$pdf->AddPage();
$pdf->setSourceFile('vorlage_2.pdf');
$tplIdx = $pdf->importPage(1);

$pdf->useTemplate($tplIdx, 0,0, $page_width);



$pdf->SetXY(25,27);
$pdf->Write(0, $name);

$pdf->Rect(122,24,39,5, "F");

$pdf->SetXY(122,27);
$pdf->Write(0, $personalnummer);

$pdf->SetXY(168,27);
$pdf->Write(0, $monat);

$pdf->SetXY(180,27);
$pdf->Write(0, $jahr);


$pdf->SetFont('Helvetica', '', $font_size_smaller);



for($j = 0; $j < 31; $j++){
	for($i = 0; $i < 22; $i++){
		$pdf->SetXY(24.5+7.85*$i, 59+$j*6.3);
		//$pdf->Write(0, "01:23");
	}
}



$last_month = UnixToMonth($complete_data[0]['date_ts']);

foreach($complete_data as $entry){
	$this_month = UnixToMonth($entry['date_ts']);
	
	if($last_month == $this_month){
		
	}else{
		//present sums

		for($i = 0; $i < count($month_sums)-1; $i++){

			$x = $i * $offset_column + $start_column;
			$y = 31 * $offset_line + $start_line;
			
			if($month_sums[$i]<=0){
				$txt = "";
			}else{
				$txt = format_time($month_sums[$i]);
			}

			$pdf->SetXY($x, $y);
			$pdf->Write(0, $txt);
			
			$month_sums[$i] = 0;
		}
		
		$pdf->AddPage();
		$pdf->useTemplate($tplIdx, 0,0, $page_width);

		$pdf->SetFont('Helvetica', '', $font_size);
		$pdf->SetTextColor(0,0,0);
		$pdf->setFillColor(255,255,255);
				
		$pdf->SetXY(25,27);
		$pdf->Write(0, $name);

		$pdf->Rect(122,24,39,5, "F");

		$pdf->SetXY(122,27);
		$pdf->Write(0, $personalnummer);

		$pdf->SetXY(168,27);
		$pdf->Write(0, $this_month);

		$pdf->SetXY(180,27);
		$pdf->Write(0, $jahr);

		$pdf->SetFontSize($font_size_smaller);

	}

	$last_month = $this_month;


	$offset_factor_line = UnixToDay($entry['date_ts']) - 1;

	$y = $offset_factor_line * $offset_line + $start_line;

	

	
	

	for($i = 0; $i < 22; $i++){
		
		$x = $i * $offset_column + $start_column;
		$txt ="";
		
		switch($i){
			case 0:
				$month_sums[0] += $entry['sonntagsarbeit'];
				$txt = format_time($entry['sonntagsarbeit']);
				break;
			case 1:
				$month_sums[1] += $entry['nacht1'];
				$txt = format_time($entry['nacht1']);
				break;
			case 2:
				$month_sums[2] += $entry['nacht2'];
				$txt = format_time($entry['nacht2']);
				break;
			case 3:
				$month_sums[3] += $entry['samstag'];
				$txt = format_time($entry['samstag']);
				break;
			case 4:
				if(isset($_POST['ueberstunden'])){
					
					if($entry['ueberstunden']<0){
						$txt = "";
					}else{
						$month_sums[4] += $entry['ueberstunden'];
						$txt = format_time($entry['ueberstunden']);
					}
				}
				break;
			default:
				$txt = "";
				break;
				
								
		}


		$pdf->SetXY($x, $y);
		$pdf->Write(0, $txt);
	}




}

for($i = 0; $i < count($month_sums)-1; $i++){

	$x = $i * $offset_column + $start_column;
	$y = 31 * $offset_line + $start_line;
	
	if($month_sums[$i]<=0){
		$txt = "";
	}else{
		$txt = format_time($month_sums[$i]);
	}

	$pdf->SetXY($x, $y);
	$pdf->Write(0, $txt);
	
	$month_sums[$i] = 0;
}




#                        ########
#                          Ende Dokument
#                        ########











//$pdf->Output("tmp/$pdf_filename.pdf","F");

//$pdf->Output("name.pdf","I", true);
//$pdf->Output($pdf_filename, "I", true);

$pdf->Output('I', 'generated.pdf');




#*******************************************************
#          Funktionen, die nicht eingebunden werden
#*******************************************************


?> 