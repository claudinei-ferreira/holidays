<?php

require __DIR__ . "/../src/Holiday.php";

use claufersus\Holiday;

/** SHOW HOLIDAYS OF THE YEAR 2021 AND MONTH JANUARY => 1 */
$year = 2021;
$month = 1;

$holiday = new Holiday($year, $month);
$holidays = $holiday->getHolidays();

if(!empty($holidays)){

    echo "<h1> January - {$year} </h1>";
    echo "<p>Holidays default of the class Holiday</p>";

    foreach($holidays as $holiday){

        echo "{$holiday->date} - {$holiday->name} <br>";

    }

}

echo "<hr>";

/** INCLUDE HOLIDAYS OF THE YOUR PREFERENCE 
 *  HOLIDAYS MUST BE SENT AN OBJECT WITH THE FOLLOWING FORMAT:
 *  DATE => month-day
 *  NAME => The Name Of Holiday
*/

$holidaysCustom = [
    ['date'  => '01-02','name' => 'Dia do Sanitarista'],
    ['date'  => '01-03','name' => 'Dia do Juiz de Menores'],
    ['date'  => '01-03','name' => 'Dia Mundial do Braille'],
    ['date'  => '02-14','name' => 'Dia de São Valentim'],
    ['date'  => '06-05','name' => 'Dia mundial do meio ambiente'],
    ['date'  => '06-12','name' => 'Dia dos namorados'],    
];
$holidaysCustom = json_decode(json_encode((object) $holidaysCustom), FALSE);

$months = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junnho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'];

for($i=1; $i <= 12; $i++){

    echo "<h1>Feriados de {$months[$i]} - {$year} </h1>";
    $holiday = new Holiday($year, $i);
    $holiday->includeHolidays($holidaysCustom);       
    $holidays= $holiday->getHolidays();
    
    if(!empty($holidays)){
        foreach($holidays as $holiday){    
            echo date('d/m/Y', strtotime($holiday->date)) . " - " . $holiday->name . "<br>";    
        }    
    }else{
        echo "Não há feriados para o mês informado";
    }

}
