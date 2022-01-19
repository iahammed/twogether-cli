<?php


use Twogether\FileManager;
use Twogether\models\Employee;
use function PHPUnit\Framework\assertEquals;

class FileManagerTest extends \PHPUnit\Framework\TestCase {

    public function test_can_read_file_from_input_to_process_as_array()
    {
        $fileData = [
            ['Steve', '1992-10-14'],
            ['Thomad Stepherson', '1992-10-14']
        ];

        $inputFile = 'input/' . 'test.txt';
        $data = (New FileManager)->fileReader($inputFile);

        $this->assertEquals($data, $fileData);
    }

    public function test_can_read_file_from_input_to_process_as_object()
    {
        $employees = [];
        
        $fileData = [
            ['Steve', '1992-10-14'],
            ['Thomad Stepherson', '1992-10-14']
        ];

        foreach ($fileData as $data){
            $employees[] = new Employee($data[0], $data[1]);
        }

        $inputFile = 'input/' . 'test.txt';
        $data = (New FileManager)->fileReaderObj($inputFile);

        $this->assertEquals($data, $employees);
    }

}