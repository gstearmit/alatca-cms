<?php
 
class HT_View_Helper_ConvertTime
{
    public function convertTime($time, $configLang)
    {
        if($configLang == "en") {
            $langHe['year'] = "year";
            $langHe['month'] = "month";
            $langHe['day'] = "day";
            $langHe['hour'] = "hour";
            $langHe['minute'] = "minute";
            $langHe['second'] = "second";
            $langHe['s ago'] = "s ago";
        }else{
            $langHe['year'] = "năm";
            $langHe['month'] = "tháng";
            $langHe['day'] = "ngày";
            $langHe['hour'] = "giờ";
            $langHe['minute'] = "phút";
            $langHe['second'] = "giây";
            $langHe['s ago'] = "cách đây";
        }
        $hour = "";
        $miniture = "";
        $secon = "";
        if($time > 3600){
            $hour = round($time/(60*60),0, PHP_ROUND_HALF_DOWN);
            $subSecon = $time-($hour*60*60);
            if($subSecon > 60) {
                $miniture = round($subSecon/60,0, PHP_ROUND_HALF_DOWN);
                $secon = $subSecon - ($miniture*60);
            }
            $return = $hour." giờ ".$miniture." phút ".$secon." giây"; 
           
        }
        
        elseif($time > 60){
            $miniture = round($time/60,0, PHP_ROUND_HALF_DOWN);
            $secon = $time - ($miniture*60);
            $return = $miniture." phút ".$secon." giây";
        }
        elseif($time < 60){
            $secon = $time;
            $return = $secon." giây";
        }
        
        return $return;
    
    }
}