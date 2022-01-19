<?php

namespace Twogether;

use Twogether\models\CakeDate;

class DataManager
{
    /** 
     *  Calculate The office is closed  
     *  @param 
     *  @return array $close
     */
    public function calculateClose(){
        $close = [];
        $yr = date("Y");
        // New year's:
        switch ( date("w", strtotime("$yr-01-01")) ) {
            case 6:
                $close[] = "$yr-01-03";
                break;
            case 0:
                $close[] = "$yr-01-02";
                break;
            default:
                $close[] = "$yr-01-01";
        }

        // Christmas and Boxing Day:
        switch ( date("w", strtotime("$yr-12-25")) ) {
            case 5:
                $close[] = "$yr-12-25";
                $close[] = "$yr-12-28";
                break;
            case 6:
                $close[] = "$yr-12-27";
                $close[] = "$yr-12-28";
                break;
            case 0:
                $close[] = "$yr-12-26";
                $close[] = "$yr-12-27";
                break;
            default:
                $close[] = "$yr-12-25";
                $close[] = "$yr-12-26";
        }
        return $close;
    }

    /** 
     * is Weekend 
     * @param string $date
     * @return boolean 
     */
    public function isWeekendOrFriday($date) {
        return (date('N', strtotime($date)) >= 5);
    }

    /** 
     * is Friday 
     * @param string $date
     * @return boolean 
     */
    public function isFriday($date) {
        return ((int)date('N', strtotime($date)) === 5);
    }

    /** 
     * is Weekend 
     * @param string $date
     * @return boolean 
     */
    public function isWeekend($date) {
        return ((int)date('N', strtotime($date)) > 5);
    }


    /** 
     * Working day after Birthday 
     * @param string $date
     * @param array $officeClose
     * @return string $backDate
     */
    public function getWorkingDate($date, $officeClose) {
        $yr = date("Y");
        $date = $yr . '-' . date('m-d', strtotime($date));
        if($this->isWeekendOrFriday($date)){
            $this->isFriday($date) ? 
                $backDate = date('Y-m-d', strtotime("next monday", strtotime($date))) :
                    $backDate = date('Y-m-d', strtotime("next tuesday", strtotime($date))); 

            $bankHolidayWeekend = false;
            $nextMonday = date('Y-m-d', strtotime("next monday", strtotime($date)));

            if(in_array($nextMonday, $officeClose)){
                $bankHolidayWeekend = true;
                $backDate = $backDate = date('Y-m-d', strtotime("next monday", strtotime($date)));
            }

            if(in_array($backDate, $officeClose)){
                $bankHolidayWeekend ? 
                    $backDate = date('Y-m-d', strtotime($backDate . ' +2 day')) :
                        $backDate = date('Y-m-d', strtotime($backDate . ' +1 day'));
                
                /** Check for Bankholiday in row */
                if($bankHolidayWeekend){
                    $beforeBackDay = date('Y-m-d', strtotime($backDate . ' -1 day'));
                    if(in_array($beforeBackDay, $officeClose)){
                        $backDate = date('Y-m-d', strtotime($backDate . ' +1 day'));
                    }
                }

                if(in_array($backDate, $officeClose)){
                    $backDate = date('Y-m-d', strtotime($backDate . ' +1 day'));
                }
                return $backDate;
            } else {
                return $backDate;
            }
        } else {
            if(in_array($date, $officeClose)){
                $backDate = date('Y-m-d', strtotime($date . ' +1 day'));
                if(in_array($backDate, $officeClose)){
                    $backDate =  date('Y-m-d', strtotime($backDate . ' +1 day'));
                }
                return date('Y-m-d', strtotime($backDate . ' +1 day'));
            }
            return date('Y-m-d', strtotime($date . ' +1 day'));
        }
    }

    /** 
     * Prepare CSV ready data
     * @param array $data
     * @param array $officeClose
     * @return array $csvData
     */
    public function prepareCsvData($data, $officeClose)
    {
        $csvData = array( 
            ['Date', 'Number of Small Cakes', 'Number of Large Cakes', 'Names of people getting cake'], 
        );
        foreach($data as $d){
            $date = $this->getWorkingDate($d[1], $officeClose);
            $previousDate = date('Y-m-d', strtotime($date . ' -1 day'));
            
            if(array_key_exists($date, $csvData)){
                $csvData[$date]['NumberOfSmallCakes'] = $csvData[$date]['NumberOfSmallCakes'] >= 1 ? $csvData[$date]['NumberOfSmallCakes'] - 1 : 0;
                $csvData[$date]['NumberOfLargeCakes'] = $csvData[$date]['NumberOfLargeCakes'] + 1;
                $name = array($csvData[$date]['NamesOfPeople'], $d[0]);
                $csvData[$date]['NamesOfPeople'] = implode(", ", $name);
            } elseif (array_key_exists($previousDate, $csvData)){
                if($csvData[$previousDate]['NumberOfLargeCakes'] > 0){
                    $date = $this->getWorkingDate(date('Y-m-d', strtotime($d[1] . ' +1 day')), $officeClose);
                    $csvData[$date]['date'] = $date;
                    $csvData[$date]['NumberOfSmallCakes'] = 1;
                    $csvData[$date]['NumberOfLargeCakes'] = 0;
                    $csvData[$date]['NamesOfPeople'] = $d[0];
                } else {
                    $name = array($csvData[$previousDate]['NamesOfPeople'], $d[0]);
                    $csvData[$date]['date'] = $date;
                    $csvData[$date]['NumberOfSmallCakes'] = 0;
                    $csvData[$date]['NumberOfLargeCakes'] = 1;
                    $csvData[$date]['NamesOfPeople'] = implode(", ", $name);
                    unset($csvData[$previousDate]);
                }
            } else {
                $csvData[$date] = [
                    'date'  =>  $date,
                    'NumberOfSmallCakes' => 1,
                    'NumberOfLargeCakes' => 0,
                    'NamesOfPeople'  =>  $d[0],
                ];
            }
        }
        // Sort the according to date
        ksort($csvData);
        return $csvData;
    }

    /** 
     * Prepare CSV ready data
     * @param array $data
     * @param array $officeClose
     * @return array $csvData
     */
    public function prepareCsvObjData($data, $officeClose)
    {
        $csvData = array( 
            ['Date', 'Number of Small Cakes', 'Number of Large Cakes', 'Names of people getting cake'], 
        );

        foreach($data as $d){
            $date = $this->getWorkingDate($d->dob, $officeClose);
            $previousDate = date('Y-m-d', strtotime($date . ' -1 day'));

            if (array_key_exists($date, $csvData)) {
                $cakeDate = $csvData[$date][0];
                $cakeDate->NumberOfSmallCakes = $cakeDate->NumberOfSmallCakes >=1 ? $cakeDate->NumberOfSmallCakes -1 : 0;
                $cakeDate->NumberOfLargeCakes = 1;
                $cakeDate->setEmployee($d);
            } elseif (array_key_exists($previousDate, $csvData)){
                $cakeDate = $csvData[$previousDate][0];
                if($cakeDate->NumberOfLargeCakes > 0){
                    $date = $this->getWorkingDate(date('Y-m-d', strtotime($d->dob . ' +1 day')), $officeClose);
                    $cakeDate->date = $date;
                    $cakeDate->NumberOfSmallCakes = 1;
                    $cakeDate->NumberOfLargeCakes = 0;
                    $cakeDate->setEmployee($d);
                } else {
                    $cakeDate->date = $date;
                    $cakeDate->NumberOfSmallCakes = 0;
                    $cakeDate->NumberOfLargeCakes = 1;
                    $cakeDate->setEmployee($d);
                    unset($csvData[$previousDate]);
                }
            } else {
                $cakeDay = new CakeDate($date, 1, 0);
                $cakeDay->setEmployee($d);
                $csvData[$date] = [
                    $cakeDay
                ];
            }

        }
        // Sort the according to date
        ksort($csvData);
        return $csvData;
    }
}