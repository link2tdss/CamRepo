<?php

	$date =  date_create();
	echo date_create()->format('Y-m-d H:i:s');
	$date1 = DateTime::createFromFormat('Y-m-d H:i:s', '2017-01-28 18:09:05');
	#echo $date->format('Y-m-d H:i:s') , $date1->format('Y-m-d H:i:s');
	$diff = $date1->diff($date,false);
	if ($date < $date1 || $diff->days > 0 || $diff->h > 0 || $diff->i > 5){
		echo 'Invalid date';
	}else{
		
		echo 'Yup';
	}
	

?>