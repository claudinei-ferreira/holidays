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
   

    
    /**
     * @param $year int
     * @param $month int
     */
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
        $this->includeHolidays($holidays);
    }

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

    private function loadHolidays()
    {
        $this->otherHolidays = [
            ['date'  => '01-01','name' => 'Confraternização Universal'],
            ['date' => '01-06','name' => 'Dia de Reis'],
            ['date' => '01-07','name' => 'Dia do Leitor'],
            ['date' => '01-31','name' => 'São João Bosco'],
            ['date' => '03-08','name' => 'Dia da Mulher'],
            ['date' => '03-12','name' => 'Dia do Bibliotecário'],
            ['date' => '03-15','name' => 'Dia da Escola'],
            ['date' => '03-19','name' => 'São José'],
            ['date' => '04-01','name' => 'Canonização de Dom Bosco (1934)'],
            ['date' => '04-09','name' => 'Dia da Biblioteca'],
            ['date' => '04-18','name' => 'Dia de Monteiro Lobato'],
            ['date' => '04-19','name' => 'Dia do Índio'],
            ['date' => '04-21','name' => 'Tiradentes'],
            ['date' => '04-22','name' => 'Descobrimento do Brasil'],
            ['date' => '04-25','name' => 'Dia do Contabilista'],
            ['date' => '04-28','name' => 'Dia da Educação'],
            ['date' => '05-01','name' => 'Dia do Trabalho (São José Operário, memória)'],
            ['date' => '05-06','name' => 'São Domingos Sávio'],
            ['date' => '05-08','name' => 'Dia do Profissional Marketing'],
            ['date' => '05-13','name' => 'Santa Maria Domingas Mazzarello'],
            ['date' => '05-24','name' => 'Nossa Senhora Auxiliadora'],
            ['date' => '06-03','name' => 'Dia do Profissional de RH'],
            ['date' => '06-13','name' => 'Dia de Santo Antônio'],
            ['date' => '06-24','name' => 'Natividade de São João Batista' ],
            ['date' => '06-29','name' => 'Dia de São Pedro' ],
            ['date' => '06-29','name' => 'Dia de São Paulo' ],
            ['date' => '07-09','name' => 'Revolução Constitucionalista de 1932' ],
            ['date' => '07-14','name' => 'Chegada dos Salesianos no Brasil' ],
            ['date' => '07-26','name' => 'Dia da Vovó' ],
            ['date' => '07-27','name' => 'Dia dos Avós' ],
            ['date' => '08-01','name' => 'Aniversário de Piracicaba'],
            ['date' => '08-04','name' => 'Dia do Padre'],
            ['date' => '08-11','name' => 'Dia do Estudante'],
            ['date' => '08-16','name' => 'Aniversário de Dom Bosco'],
            ['date' => '08-22','name' => 'Dia do Coordenador Pedagógico'],
            ['date' => '09-07','name' => 'Dia da Independência'],
            ['date' => '09-22','name' => 'Dia do Contador'],
            ['date' => '10-12','name' => 'Nossa Senhora Aparecida'],
            ['date' => '10-12','name' => 'Dia das Crianças'],
            ['date' => '10-15','name' => 'Dia do Professor'],
            ['date' => '10-19','name' => 'Dia do Profissional de TI'],
            ['date' => '11-01','name' => 'Todos os Santos'],
            ['date' => '11-02','name' => 'Finados'],
            ['date' => '11-15','name' => 'Proclamação da República'],
            ['date' => '11-19','name' => 'Dia da Bandeira'],
            ['date' => '11-20','name' => 'Dia Da Consciência Negra'],
            ['date' => '12-04','name' => 'Dia do Orientador Educacional'],
            ['date' => '12-08','name' => 'Imaculada Conceição'],
            ['date' => '12-24','name' => 'Véspera de Natal'],
            ['date' => '12-25','name' => 'Natal'],
            ['date' => '12-30','name' => 'Sagrada Família, Jesus, Maria e José'],
            ['date' => '12-31','name' => 'Véspera de Ano Novo']
        ];

        return json_decode(json_encode((object) $this->otherHolidays), FALSE);
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