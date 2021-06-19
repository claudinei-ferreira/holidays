<?php

namespace claufersus;

use Exception;

/**
 * Class Claufersus Holiday
 * 
 * @author Claudinei Ferreira de Jesus
 * @package Claufersus\Holiday
 */
class Holiday
{

    /** var integer */
    private $year;

    /** var integer */
    private $month; 
    
    /** var array */
    private $holidays = array();

    /** var string */
    private $version = "0.1.3";
   

    
    /**
     * @param $year int
     * @param $month int
     */
    public function __construct($year, $month)
    {
        $this->setYear($year);
        $this->setMonth($month);        
    }

    /**
     * Return object of the holidays
     */
    public function getHolidays()
    {        
        $this->createHolidays();
        return $this->reorderHolidays();		
    }


    public function setMonth($month)
    {
        if(is_int($month) && $month <= 12 && $month >= 1){
            $this->month = $month;
        }else{
            throw new Exception("Erro: o valor definido para mês não é válido: ". $month);
        }
    }

    /**
     * @param $year integer
     * Set the Year
     */
    public function setYear($year)
    {
        if(is_int($year) && $year >= 1970 && $year <= 2099){
            $this->year = $year;
        }else{
            throw new Exception("Error: value set for year is not valid ". $year);
        }
    }


    /** 
     * Include holidays or others events from object
     * @param $holidays object
     * 
     */
    public function includeHolidays(object $holidays)
    {
        foreach($holidays as $holiday){
            $date = "{$this->year}-{$holiday->date}";
			$this->setHoliday(strtotime($date), $holiday->name);			
        }    
    }

    /** 
     * Method responsible of the send holidays for method setHoliday
     * Add easterdate
     * Add Dynamic Holidays
     * Add Holidays include by user
     */
    private function createHolidays()
    {
        // Easter based holidays
        $this->setEasterDate();

        // Dynamic holidays
        $this->setHoliday($this->getSecondSunday(5), "Dia das mães");
        $this->setHoliday($this->getSecondSunday(8), "Dia dos pais");
        $this->setHoliday($this->getProgrammerDay($this->year), "Dia do Programador");
        
        // Other Holidays
        $holidays = $this->loadHolidays();
        if(!empty($holidays)){
            $this->includeHolidays($holidays);
        }
    }

    /**
     * Method responsible add holiday in array one by one
     * @param $timestamp => timestamp of the holiday
     * @param $name => The Name of the holiday     * 
     */
    private function setHoliday($timestamp, $name)
    {
        if (date("n", $timestamp) == $this->month) {

            $this->holidays[] = [
                'day' => str_pad(date('j', $timestamp), 2, 0, STR_PAD_LEFT),
                'date' => date('Y-m-d', $timestamp),
                'name' => $name,
            ];
        }
    }

    /**
     * Set easter date of the year and easter based holidays
     */
    private function setEasterDate()
    {
        // Feriados baseados na páscoa
        $easter = easter_date($this->year); // Páscoa
        $this->setHoliday($easter, "Domingo da Páscoa na Ressurreição do Senhor");
        $this->setHoliday(strtotime("-47 days", $easter), "Terça-Feira - Carnaval");
        $this->setHoliday(strtotime("-46 days", $easter), "Quarta-feira de cinzas");
        $this->setHoliday(strtotime("-7 days", $easter),  "Domingo de Ramos");
        $this->setHoliday(strtotime("-3 days", $easter),  "Quinta-Feira Santa");
        $this->setHoliday(strtotime("-2 days", $easter), "Sexta-Feira da Paixão do Senhor");
        $this->setHoliday(strtotime("-1 days", $easter), "Sábado Santo - Vigília Pascal");
        $this->setHoliday(strtotime("+39 days", $easter), "Ascensão do Senhor");
        $this->setHoliday(strtotime("+49 days", $easter), "Pentecostes");
        $this->setHoliday(strtotime("+60 days", $easter), "Corpus Cristhi");
    }

    /**
     * LoadHolidays: load holidays from file json
     */
    private function loadHolidays()
    {
        $file = file_get_contents(__DIR__ . '/holidays.json');
        return json_decode(json_encode((object) json_decode($file)), FALSE);
    }


    /**
     * @param $year integer
     * @return mktime for programmer day
     * O 256º dia do ano é o dia que comemoramos a profissão dos que programam.
     * Muitas vezes é o dia 13 de setembro, mas nos anos bissextos isso cai no dia 12 de setembro.
     */
    private function getProgrammerDay($year){
        if($this->getIsYearBissexto($year)):
            return mktime(0, 0, 0, 9, 12, $year);
        else:
            return mktime(0, 0, 0, 9, 13, $year);
        endif;
    }

    /** 
     * @param $year integer
     * @return boolean 
     */
    private function getIsYearBissexto($year){
        if(date('L', mktime(0, 0, 0, 1, 1, $year))):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * @param $month integer
     * @return mktime of the second sunday of the month
     */
    private function getSecondSunday($month)
    {
        $timestamp = strtotime("-1 day", mktime(0, 0, 0, $month, 1, $this->year));
        $dayInWeek = date('w', $timestamp);
        $day = date('d', $timestamp) - $dayInWeek;
        
        for ($dayWeek = 0; $dayWeek <= $dayInWeek; $dayWeek++) {
            $arrayDays[$day++] = $dayWeek;
        }

        for ($day = 1; $day < 14; $day++) {
            $arrayDays[$day] = date("w", mktime(0, 0, 0, $month, $day, $this->year));
        }

        $c = 0;
        foreach ($arrayDays as $d => $w) {
            if ($w == 0 && $d < 20) $c++;
            if ($c == 2) return mktime(0, 0, 0, $month, $d, $this->year);

        }
    }

    /**
     * Return array of Holidays in order of the day
     */
    private function reorderHolidays()
    {
        if(empty($this->holidays)){
            return null;
        }

        $arr = $this->holidays;

        $sort = array();
        foreach($arr as $k=>$v) {

            $month = date("n", strtotime($v['date']));

            if($month == $this->month){
                $sort['day'][$k] = $v['day'];
                $sort['date'][$k] = $v['date'];
                $sort['name'][$k] = $v['name'];
            }    
        }
        array_multisort($sort['day'], SORT_ASC, $sort['date'], SORT_ASC,$arr);
        return json_decode(json_encode($arr));				
    }
    

}