<pre>
<?php
	include("functions.php");
	$holiday_array = array();

	array_push($holiday_array, "01.01.2021", "02.04.2021", "05.04.2021", "01.05.2021", "13.05.2021", "24.05.2021", "03.10.2021", "31.10.2021", "25.12.2021", "26.12.2021");
	array_push($holiday_array, "01.01.2022", "15.04.2022", "18.04.2022", "01.05.2022", "26.05.2022", "06.06.2022", "03.10.2022", "31.10.2022", "25.12.2022", "26.12.2022");


	foreach ($holiday_array as $holiday){
		echo TimeToUnix($holiday);
		echo "\n";
	}
?>
</pre>