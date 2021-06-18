<?php

namespace claufersus;

use Exception;

class Holiday
{

    private $year;
    private $month;
    private $holidays = array();

    
    public function __construct($year, $month)
    {
        $this->setYear($year);
        $this->setMonth($month);        
    }


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

    public function setYear($year)
    {
        if(is_int($year) && $year >= 1970 && $year <= 2099){
            $this->year = $year;
        }else{
            throw new Exception("Error: value set for year is not valid ". $year);
        }
    }


    private function createHolidays()
    {
        // Feriados da Páscoa e que se baseiam na páscoa
        $this->setEasterDate();

        // Feriados Dinâmicos
        $this->setHoliday($this->getSecondSunday(5), "Dia das mães");
        $this->setHoliday($this->getSecondSunday(8), "Dia dos pais");
        $this->setHoliday($this->getProgrammerDay($this->year), "Dia do Programador");
    }

    private function setHoliday($timestamp, $description)
    {
        if (date("n", $timestamp) == $this->month) {

            $this->holidays[] = [
                'day' => str_pad(date('j', $timestamp), 2, 0, STR_PAD_LEFT),
                'date' => date('Y-m-d', $timestamp),
                'name' => $description,
            ];

        }
    }

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

    private function getProgrammerDay($year){
        if($this->getIsYearBissexto($year)):
            return mktime(0, 0, 0, 9, 12, $year);
        else:
            return mktime(0, 0, 0, 9, 13, $year);
        endif;
    }

    private function getIsYearBissexto($year){
        if(date('L', mktime(0, 0, 0, 1, 1, $year))):
            return true;
        else:
            return false;
        endif;
    }

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


    private function reorderHolidays()
    {
        if(empty($this->holidays)){
            return null;
        }


        $arr = $this->holidays;

        $sort = array();
        foreach($arr as $k=>$v) {
            $sort['day'][$k] = $v['day'];
            $sort['date'][$k] = $v['date'];
            $sort['name'][$k] = $v['name'];
        }
        array_multisort($sort['day'], SORT_ASC, $sort['date'], SORT_ASC,$arr);
        return json_decode(json_encode($arr));
    }
    

}