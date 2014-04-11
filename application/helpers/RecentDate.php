<?php
 
class HT_View_Helper_RecentDate
{
    public function recentDate($time, $configLang)
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
        $from = null;
        if ($from == null) {
            $from = time();
        }
        $time = $from - $time;
 
        $chunks = array(
            array(60 * 60 * 24 * 365 , $langHe['year']),
            array(60 * 60 * 24 * 30 , $langHe['month']),
            array(60 * 60 * 24 , $langHe['day']),
            array(60 * 60 , $langHe['hour']),
            array(60 , $langHe['minute']),
            array(1 , $langHe['second'])
        );
 
        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($time / $seconds)) != 0) {
                break;
            }
        }
        if($configLang == "en") {
        $print = ($count == 1 || $count == 0) ? '1 '.$name : "$count {$name}".$langHe['s ago'];
        }else {
        $print = ($count == 1 || $count == 0) ? '1 '.$name : $langHe['s ago']." $count {$name}";    
        }
        return $print;
    
    }
}