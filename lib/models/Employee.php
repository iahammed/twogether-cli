<?php

namespace Twogether\models;

class Employee {

    public $name;
    public $dob;

    public function __construct($name, $dob)
    {
        $this->name = $name;
        $this->dob = $this->formatDate($dob);
    }

    protected function formatDate($date, $format = 'Y-m-d'){
        $phpdate = strtotime( $date );
        $mysqldate = date( $format, $phpdate );
        return $mysqldate;
    }

}