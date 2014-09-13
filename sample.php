<?php

//include charts.php to access the SendChartData function
include "charts.php";;

   // Create the array
   $chart['chart_data'] = array ( 
           array ("","Que edad Tiene"),
           array ( "Entre 5 y 10", 1),
           array ( "Entre 10 y 15", 2 ),
		   array ("Entre 20 y 25", 4),
   );

   // Build the chart
   SendChartData($chart);


SendChartData ();

?>