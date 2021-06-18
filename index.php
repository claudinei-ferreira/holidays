<?php

use src\Holiday;

require 'src/Holiday.php';

$holidays = new Holiday(2021,5);
$holidays = $holidays->getHolidays();

if(!empty($holidays)){
    foreach($holidays as $holiday){
        echo  date('d/m/Y', strtotime($holiday->date)) . " -  {$holiday->name} <br>";
    }
}

