<?php

namespace Twogether;

class App
{
    protected $dataManager;

    public function __construct()
    {
        $this->dataManager = new DataManager();
        $this->fileManager = New FileManager();
    }
    
    public function getFileManager()
    {
        return $this->fileManager;
    }

    public function getDataMaanger()
    {
        return $this->dataManager;
    }

    public function runCommand(array $argv)
    {
        if(!isset($argv[1])){
            echo 'Please provied input file name';
            return;
        } else {
            $inputFile = 'input/' . $argv[1];
            $outFile = 'output/' .  'output.csv';
            $data = $this->getFileManager()->fileReader($inputFile);
            if(count($data) <= 0){
                echo 'This file do not contents any usefull information';
                return;
            }
            $officeClose = $this->getDataMaanger()->calculateClose();
            $csvData = $this->getDataMaanger()->prepareCsvData($data, $officeClose);
            if(count($csvData) <=0 ){
                echo 'This file do not contents any usefull information';
                return;
            }
            $this->getFileManager()->outputCsv($outFile, $csvData);
            echo 'Please find your  procesed file at : ' . $outFile;
        }
    }
}