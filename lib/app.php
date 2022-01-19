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
            $officeClose = $this->getDataMaanger()->calculateClose();

            /**
             *  ARRAY RELATE
             */
            // $data = $this->getFileManager()->fileReader($inputFile);
            // if(count($data) <= 0){
            //     echo 'This file do not contents any usefull information';
            //     return;
            // }
            // $csvData = $this->getDataMaanger()->prepareCsvData($data, $officeClose);
            // if(count($csvData) <=0 ){
            //     echo 'This file do not contents any usefull information';
            //     return;
            // }
            // $this->getFileManager()->outputCsv($outFile, $csvData);
            // echo 'Please find your  procesed file at : ' . $outFile;

            /** -------- ARRAY RELATE END---------- */

            /**
             * OBJECT RELATED
             */
            $data = $this->getFileManager()->fileReaderObj($inputFile);
            $csvData = $this->getDataMaanger()->prepareCsvObjData($data, $officeClose);

            if(count($csvData) <=0 ){
                echo 'This file do not contents any usefull information';
                return;
            }

            $this->getFileManager()->outputCsvFromObj($outFile, $csvData);
            echo 'Please find your  procesed file at : ' . $outFile;
            /** -------- OBJECT RELATED RELATE END---------- */
        }
    }
}