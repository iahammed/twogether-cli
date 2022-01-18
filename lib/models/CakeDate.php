<?php

namespace Twogether\models;

class CakeDate {

    public $date;
    public $smallCake;
    public $largeCake;
    public $employees = [];

    public function __construct( $date, $smallCake = 0, $largeCake = 0 )
    {
        $this->date = $date;
        $this->smallCake = $smallCake;
        $this->largeCake = $largeCake;
    }

    public function date(){
        return $this->date;
    }

    public function smallCake()
    {
        return $this->smallCake;
    }

    public function largeCake()
    {
        return $this->largeCake;
    }

    public function setEmployee(Employee $employee)
    {
        array_push( $this->employees, $employee->name );
    }
}