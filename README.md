# Holidays @Claufersus

## About Holidays

###### Package for generating Holidays for a given year
Pacote para geração de Feriados de um determinado ano


## Installation

Holidays is available via Composer:

```bash
"composer require claufersus/holidays"
```


## Documentation

###### For more details on using holiday, see an example folder in the component directory. In it will have an example of the use of the class. Clausfersus Holiday works like this:

Para mais detalhes sobre como usar holiday, veja uma pasta de exemplo no diretório do componente. Nela terá um exemplo de uso da classe. Claufersus Holiday funciona assim:

### Show Holidays

```php
require __DIR__ . "/vendor/autoload.php";

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

```


### Add news Holidays 

##### By default the Holiday class returns some holidays and commemorative dates. But if you want to send other holidays or celebrations use the following method:

Por padrão a classe Holiday retorna alguns feriados e datas comemorativas. Mas se você desejar enviar outros feriados ou comemorações utilize o método a seguir: 

```php

<?php

require __DIR__ . "/vendor/autoload.php";

use claufersus\Holiday;

/** SHOW HOLIDAYS OF THE YEAR 2021 AND MONTH JANUARY => 1 */
$year = 2021;
$month = 1;

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

```


