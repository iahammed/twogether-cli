<?php

namespace Twogether\models;

class CakeDate {

    public $date;
    public $NumberOfSmallCakes;
    public $NumberOfLargeCakes;
    public $employees = [];

    public function __construct( $date, $NumberOfSmallCakes = 0, $NumberOfLargeCakes = 0 )
    {
        $this->date = $date;
        $this->NumberOfSmallCakes = $NumberOfSmallCakes;
        $this->NumberOfLargeCakes = $NumberOfLargeCakes;
    }

    public function date(){
        return $this->date;
    }

    public function setEmployee(Employee $employee)
    {
        array_push( $this->employees, $employee->name );
    }
}