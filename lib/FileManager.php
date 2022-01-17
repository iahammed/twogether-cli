<?php

namespace Twogether;

class FileManager
{
    /**
     *  Read File and return data array
     *  @param string $filename
     *  @return array $data 
     */
    public function fileReader($filename)
    {
        $data = [];
        if(file_exists($filename)){
            $lines = file($filename, FILE_IGNORE_NEW_LINES);
            foreach ($lines as $line) {
                $data[] = explode(',' , $line);
            }
        } 
        return $data;
    }

    /**
     * Takes in a filename and an array associative data array and outputs a csv file
     * @param string $fileName
     * @param array $assocDataArray     
     */
    public function outputCsv($file, $assocDataArray)
    {
        $fp = fopen("$file", 'w');
        foreach ($assocDataArray as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        return true;
    }
}
