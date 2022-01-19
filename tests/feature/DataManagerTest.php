<?php

use Twogether\DataManager;
use Twogether\models\CakeDate;
use Twogether\models\Employee;

class DataManagerTest extends  \PHPUnit\Framework\TestCase {

    public function test_does_it_provide_currect_office_close_other_than_weekends()
    {
        $current = ['2022-01-03', '2022-12-26', '2022-12-27'];
        $officeClose = $this->getClose();

        $this->assertEquals($officeClose, $current);
    }

    public function test_it_calculate_correct_birthay_off()
    {
        $officeClose = $this->getClose();
        $birthDay = '1978-02-02';
        $backDate = '2022-02-03';
        $checkBack = (new DataManager)->getWorkingDate($birthDay, $officeClose);
        
        $this->assertEquals($checkBack, $backDate);
    }

    public function test_it_calculate_friday()
    {
        $thisFriday = date( 'Y-m-d', strtotime( 'friday this week' ) );
        $checkFriday = (new DataManager)->isFriday($thisFriday);

        $this->assertTrue($checkFriday);
    }

    public function test_it_calculate_weekend_and_friday()
    {
        $thisWeekend = date( 'Y-m-d', strtotime( 'saturday this week' ) );
        $checkWeekend = (new DataManager)->isWeekendOrFriday($thisWeekend);

        $this->assertTrue($checkWeekend);
    }

    public function test_it_calculate_back_day_if_dob_is_on_friday()
    {
        $thisFriday = date( 'Y-m-d', strtotime( 'friday this week' ) );
        $backDate = date( 'Y-m-d', strtotime( 'monday next week' ) );
        $checkBack = (new DataManager)->getWorkingDate($thisFriday, []);

        $this->assertEquals($checkBack, $backDate);
    }

    public function test_it_calculate_back_day_if_dob_is_on_saturday()
    {
        $thisSaturday = date( 'Y-m-d', strtotime( 'saturday this week' ) );
        $backDate = date( 'Y-m-d', strtotime( 'tuesday next week' ) );
        $checkBack = (new DataManager)->getWorkingDate($thisSaturday, []);

        $this->assertEquals($checkBack, $backDate);
    }

    public function test_it_calculate_back_day_if_dob_is_on_sunday()
    {
        $thisSunday = date( 'Y-m-d', strtotime( 'sunday this week' ) );
        $backDate = date( 'Y-m-d', strtotime( 'tuesday next week' ) );
        $checkBack = (new DataManager)->getWorkingDate($thisSunday, []);

        $this->assertEquals($checkBack, $backDate);
    }

    public function test_it_calculate_back_day_if_dob_is_on_bank_holiday()
    {
        $officeClose = $this->getClose();
        $birthDay = '1978-01-03';
        $backDate = '2022-01-05';
        $checkBack = (new DataManager)->getWorkingDate($birthDay, $officeClose);
        
        $this->assertEquals($checkBack, $backDate);
    }


    public function test_it_calculate_back_day_if_dob_is_on_weekend_before_any_bank_holiday()
    {   
        $officeClose = $this->getClose();
        $birthDay = '1978-01-01';
        $backDate = '2022-01-05';
        $checkBack = (new DataManager)->getWorkingDate($birthDay, $officeClose);
        
        $this->assertEquals($checkBack, $backDate);
    }

    public function test_it_calculate_back_day_if_dob_is_on_bank_holiday_in_row()
    {
        $officeClose = $this->getClose();
        /**
         * Add Two Days in row to simulate bankholiday in row because this year 
         * chiristmans holiday is on weekend 
         */
        array_push($officeClose, "2022-12-13", "2022-12-14");
        $birthDay = '1978-12-13';
        $backDate = '2022-12-16';
        $checkBack = (new DataManager)->getWorkingDate($birthDay, $officeClose);
        
        $this->assertEquals($checkBack, $backDate);
    }

    public function test_it_calculate_back_day_if_dob_is_on_weekend_before_any_bank_holiday_in_row()
    {   
        $officeClose = $this->getClose();
        $birthDay = '1978-12-25';
        $backDate = '2022-12-29';
        $checkBack = (new DataManager)->getWorkingDate($birthDay, $officeClose);
        
        $this->assertEquals($checkBack, $backDate);
    }

    public function test_it_process_data_for_csv_with_same_dob()
    {
        $fileData = [
            ['Steve', '1992-10-14'],
            ['Thomad Stepherson', '1992-10-14']
        ];

        $resultShouldBe =  [
            'date' => '2022-10-17',
            'NumberOfSmallCakes' => 0,
            'NumberOfLargeCakes' => 1,
            'NamesOfPeople' => "Steve, Thomad Stepherson"
        ];

        $officeClose = $this->getClose();
        $csvData = (new DataManager)->prepareCsvData($fileData, $officeClose);
        $this->assertEquals($csvData['2022-10-17'], $resultShouldBe);
    }

    public function test_dave_dob_friday_24th_June_1986_get_small_cake_on_monday_27th()
    {
        $fileData = [
            ['Dave', '1986-06-24']
        ];
        $resultShouldBe = [
            'date' => '2022-06-27',
            'NumberOfSmallCakes' => 1,
            'NumberOfLargeCakes' => 0,
            'NamesOfPeople' => "Dave"
        ];
        $officeClose = $this->getClose();
        $csvData = (new DataManager)->prepareCsvData($fileData, $officeClose);
        $this->assertEquals($csvData['2022-06-27'], $resultShouldBe);
    }

    public function test_rob_dob_3rd_July_1950_Sunday_get_small_cake_Tuesday_7th_July()
    {
        $fileData = [
            ['Rob', '1950-07-03']
        ];
        $resultShouldBe = [
            'date' => '2022-07-05',
            'NumberOfSmallCakes' => 1,
            'NumberOfLargeCakes' => 0,
            'NamesOfPeople' => "Rob"
        ];
        $officeClose = $this->getClose();
        $csvData = (new DataManager)->prepareCsvData($fileData, $officeClose);
        $this->assertEquals($csvData['2022-07-05'], $resultShouldBe);
    }

    public function test_Sam_dob_is_Monday_11th_July_and_Kate_is_Tuesday_12th_July_They_share_a_large_cake_on_Wednesday_13th_July()
    {
        $fileData = [
            ['Sam', '1950-07-11'],
            ['Kate', '1950-07-12'],
        ];

        $resultShouldBe = [
            'date' => '2022-07-13',
            'NumberOfSmallCakes' => 0,
            'NumberOfLargeCakes' => 1,
            'NamesOfPeople' => "Sam, Kate"
        ];

        $officeClose = $this->getClose();
        $csvData = (new DataManager)->prepareCsvData($fileData, $officeClose);
        $this->assertEquals($csvData['2022-07-13'], $resultShouldBe);

    }

    public function test_Alex_Jen_and_Pete_have_birthdays_on_the_18th_19th_and_20th_of_July_Alex_and_Jen_share_a_large_cake_on_Wednesday_20th_Pete_gets_a_small_cake_on_Friday_22th()
    {
        $fileData = [
            ['Alax', '1950-07-18'],
            ['Jen', '1950-07-19'],
            ['Pete', '1950-07-20'],
        ];

        $resultShouldBe_alex_jen = [
            'date' => '2022-07-20',
            'NumberOfSmallCakes' => 0,
            'NumberOfLargeCakes' => 1,
            'NamesOfPeople' => "Alax, Jen"
        ];

        $resultShouldBe_pete = [
            'date' => '2022-07-22',
            'NumberOfSmallCakes' => 1,
            'NumberOfLargeCakes' => 0,
            'NamesOfPeople' => "Pete"
        ];

        $officeClose = $this->getClose();
        $csvData = (new DataManager)->prepareCsvData($fileData, $officeClose);
        $this->assertEquals($csvData['2022-07-20'], $resultShouldBe_alex_jen);
        $this->assertEquals($csvData['2022-07-22'], $resultShouldBe_pete);
    }

    public function test_obj_rob_dob_3rd_July_1950_Sunday_get_small_cake_Tuesday_7th_July()
    {
        $fileData = [
            ['Rob', '1950-07-03']
        ];
        foreach($fileData as $data){
            $emp[] = new Employee($data[0], $data[1]);
        }

        $resultShouldBe = (new CakeDate('2022-07-05', 1, 0));
        $resultShouldBe->setEmployee($emp[0]);
        
        $officeClose = $this->getClose();
        $csvData = (new DataManager)->prepareCsvObjData($emp, $officeClose);
        
        $this->assertEquals($csvData['2022-07-05'][0], $resultShouldBe);
    }


    public function test_Obj_Sam_dob_is_Monday_11th_July_and_Kate_is_Tuesday_12th_July_They_share_a_large_cake_on_Wednesday_13th_July()
    {
        $fileData = [
            ['Sam', '1950-07-11'],
            ['Kate', '1950-07-12'],
        ];
        $employees = [];
        $resultShouldBe = (new CakeDate('2022-07-13', 0, 1));

        foreach($fileData as $data){
            $employee = new Employee($data[0], $data[1]);
            $employees[] = $employee;
            $resultShouldBe->setEmployee($employee);
        }
        $officeClose = $this->getClose();
        $csvData = (new DataManager)->prepareCsvObjData($employees, $officeClose);

        $this->assertEquals($csvData['2022-07-13'][0], $resultShouldBe);
    }

    public function test_obj_Alex_Jen_and_Pete_have_birthdays_on_the_18th_19th_and_20th_of_July_Alex_and_Jen_share_a_large_cake_on_Wednesday_20th_Pete_gets_a_small_cake_on_Friday_22th()
    {
        $fileData = [
            ['Alax', '1950-07-18'],
            ['Jen', '1950-07-19'],
            ['Pete', '1950-07-20'],
        ];

        $fileDataAlexJen = [
            ['Alax', '1950-07-18'],
            ['Jen', '1950-07-19'],
        ];

        $fileDataPete = [
            ['Pete', '1950-07-20'],
        ];

        $employees = [];

        $resultAlexJen = (new CakeDate('2022-07-20', 0, 1));
        $resultPete = (new CakeDate('2022-07-22', 1, 0));

        foreach($fileDataAlexJen as $data){
            $employee = new Employee($data[0], $data[1]);
            $resultAlexJen->setEmployee($employee);
            $employees[] = $employee;
        }

        foreach($fileDataPete as $data){
            $employee = new Employee($data[0], $data[1]);
            $resultPete->setEmployee($employee);
            $employees[] = $employee;
        }

        $officeClose = $this->getClose();
        $csvData = (new DataManager)->prepareCsvObjData($employees, $officeClose);
        
        $this->assertEquals($csvData['2022-07-20'][0], $resultAlexJen);
        $this->assertEquals($csvData['2022-07-22'][0], $resultPete);
    }

    public function getClose()
    {
        return (new DataManager)->calculateClose();
    }

}