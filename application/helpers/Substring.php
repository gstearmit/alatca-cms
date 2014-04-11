<?php
class HT_View_Helper_Substring
{
    public function Substring($chuoi, $gioihan)
    {
    if(strlen($chuoi)<=$gioihan) 
    { 
        return $chuoi; 
    } 
    else{ 
        
        if(strpos($chuoi," ",$gioihan) > $gioihan){ 
            $new_gioihan=strpos($chuoi," ",$gioihan); 
            $new_chuoi = substr($chuoi,0,$new_gioihan)."..."; 
            return $new_chuoi; 
        } 
        $new_chuoi = substr($chuoi,0,$gioihan)."..."; 
        return $new_chuoi;
    }
    }
}