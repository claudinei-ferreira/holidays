<?php

namespace claufersus;

use Exception;

class Holiday
{

    private $year;
    private $month;   
    private $holidays = array();
    private $repository = array();

    
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
     * @holidays
     */
    public function includeHolidays(object $holidays)
    {
        foreach($holidays as $holiday){
            $date = "{$this->year}-{$holiday->date}";
            $this->setHoliday(strtotime($date), $holiday->title);
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

    private function loadHolidays()
    {
        $this->otherHolidays = [
            ['date'  => '01-01','title' => 'Confraternização Universal'],
            ['date' => '01-06','title' => 'Dia de Reis'],
            ['date' => '01-07','title' => 'Dia do Leitor'],
            ['date' => '01-31','title' => 'São João Bosco'],
            ['date' => '03-08','title' => 'Dia da Mulher'],
            ['date' => '03-12','title' => 'Dia do Bibliotecário'],
            ['date' => '03-15','title' => 'Dia da Escola'],
            ['date' => '03-19','title' => 'São José'],
            ['date' => '04-01','title' => 'Canonização de Dom Bosco (1934)'],
            ['date' => '04-09','title' => 'Dia da Biblioteca'],
            ['date' => '04-18','title' => 'Dia de Monteiro Lobato'],
            ['date' => '04-19','title' => 'Dia do Índio'],
            ['date' => '04-21','title' => 'Tiradentes'],
            ['date' => '04-25','title' => 'Dia do Contabilista'],
            ['date' => '04-28','title' => 'Dia da Educação'],
            ['date' => '05-01','title' => 'Dia do Trabalho (São José Operário, memória)'],
            ['date' => '05-06','title' => 'São Domingos Sávio'],
            ['date' => '05-08','title' => 'Dia do Profissional Marketing'],
            ['date' => '05-13','title' => 'Santa Maria Domingas Mazzarello'],
            ['date' => '05-24','title' => 'Nossa Senhora Auxiliadora'],
            ['date' => '06-03','title' => 'Dia do Profissional de RH'],
            ['date' => '06-13','title' => 'Dia de Santo Antônio'],
            ['date' => '06-24','title' => 'Natividade de São João Batista' ],
            ['date' => '06-29','title' => 'Dia de São Pedro' ],
            ['date' => '06-29','title' => 'Dia de São Paulo' ],
            ['date' => '07-09','title' => 'Revolução Constitucionalista de 1932' ],
            ['date' => '07-14','title' => 'Chegada dos Salesianos no Brasil' ],
            ['date' => '07-26','title' => 'Dia da Vovó' ],
            ['date' => '07-27','title' => 'Dia dos Avós' ],
            ['date' => '08-01','title' => 'Aniversário de Piracicaba'],
            ['date' => '08-04','title' => 'Dia do Padre'],
            ['date' => '08-11','title' => 'Dia do Estudante'],
            ['date' => '08-16','title' => 'Aniversário de Dom Bosco'],
            ['date' => '08-22','title' => 'Dia do Coordenador Pedagógico'],
            ['date' => '09-07','title' => 'Dia da Independência'],
            ['date' => '09-22','title' => 'Dia do Contador'],
            ['date' => '10-12','title' => 'Nossa Senhora Aparecida'],
            ['date' => '10-12','title' => 'Dia das Crianças'],
            ['date' => '10-15','title' => 'Dia do Professor'],
            ['date' => '10-19','title' => 'Dia do Profissional de TI'],
            ['date' => '11-01','title' => 'Todos os Santos'],
            ['date' => '11-02','title' => 'Finados'],
            ['date' => '11-15','title' => 'Proclamação da República'],
            ['date' => '11-19','title' => 'Dia da Bandeira'],
            ['date' => '11-20','title' => 'Dia Da Consciência Negra'],
            ['date' => '12-04','title' => 'Dia do Orientador Educacional'],
            ['date' => '12-08','title' => 'Imaculada Conceição'],
            ['date' => '12-24','title' => 'Véspera de Natal'],
            ['date' => '12-25','title' => 'Natal'],
            ['date' => '12-30','title' => 'Sagrada Família, Jesus, Maria e José'],
            ['date' => '12-31','title' => 'Véspera de Ano Novo']
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
            $sort['day'][$k] = $v['day'];
            $sort['date'][$k] = $v['date'];
            $sort['name'][$k] = $v['name'];
        }
        array_multisort($sort['day'], SORT_ASC, $sort['date'], SORT_ASC,$arr);
        return json_decode(json_encode($arr));
    }
    

}