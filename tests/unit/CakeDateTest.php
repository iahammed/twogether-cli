<?php

use Twogether\models\CakeDate;
use Twogether\models\Employee;

class CakeDateTest extends \PHPUnit\Framework\TestCase {

    public function testThisCanCreateCakeDate()
    {
        $emp = new Employee('Ivan', '1973-04-25');
        $cakeDay = new CakeDate('2022-10-10', 1, 0);
        $cakeDay->setEmployee($emp);
        $this->assertEquals($cakeDay->date, '2022-10-10');
        $this->assertEquals($cakeDay->smallCake, 1);
        $this->assertEquals($cakeDay->largeCake, 0);
        $this->assertEquals($cakeDay->employees, ['Ivan']);
    }    

    public function testThisCanHaveMultipleEmployee()
    {
        $emp1 = new Employee('Ivan', '1973-04-25');
        $emp2 = new Employee('Kuheli', '1978-02-02');

        $cakeDay = new CakeDate('2022-10-10', 1, 0);
        $this->assertEquals($cakeDay->date(), '2022-10-10');
        $this->assertEquals($cakeDay->smallCake, 1);
        $this->assertEquals($cakeDay->largeCake, 0);
        $cakeDay->setEmployee($emp1);
        $cakeDay->setEmployee($emp2);
        $this->assertEquals($cakeDay->employees, ['Ivan', 'Kuheli']);
    }    




}