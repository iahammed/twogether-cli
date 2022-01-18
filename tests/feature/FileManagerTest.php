<?php


use Twogether\FileManager;
use function PHPUnit\Framework\assertEquals;

class FileManagerTest extends \PHPUnit\Framework\TestCase {

    public function test_can_read_file_from_input_to_process()
    {
        $fileData = [
            ['Steve', '1992-10-14'],
            ['Thomad Stepherson', '1992-10-14']
        ];

        $inputFile = 'input/' . 'test.txt';
        $data = (New FileManager)->fileReader($inputFile);

        $this->assertEquals($data, $fileData);
    }

}