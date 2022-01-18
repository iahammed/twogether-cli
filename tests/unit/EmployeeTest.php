
<?php

use Twogether\models\Employee;

class EmployeeTest extends \PHPUnit\Framework\TestCase {

    public function testThisCanCreateEmplyee()
    {
        $emp = new Employee('Ivan', '1973-04-25');
        $this->assertEquals($emp->name, 'Ivan');
        $this->assertEquals($emp->dob, '1973-04-25');
    }
    
}