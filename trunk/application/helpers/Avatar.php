<?php
class HT_View_Helper_Avatar
{
    public function Avatar($user_name, $option=array())
    {
        $stringOption = "";
        if($option != NULL){
        
            foreach($option as $kO=>$vO):
                $stringOption .= $kO." = '".$vO."' "; 
            endforeach;
        }
        $wCrop = $option['width'];
        $hCrop = $option['height'];
        $model = new HT_Model_user_models_user();
        $info           = $model->getUserInfo($user_name);
        @$name           = $info[0]['firstname'] . " " . $info[0]['lastname'];
        @$avatar         = $info[0]['avatar'];
        @$sex            = $info[0]['sex'];
        if($avatar == NULL){
                
               if($sex == 1){
                $link   = WEB_PATH . '/public/images/female_155.png';
               }else{
                $link   = WEB_PATH . '/public/images/male_150.png';
               }
        }else{
            $link   = WEB_PATH . '/application/album/' . $user_name . "/" . $avatar;
        }
        $return = '<a href="'.WEB_PATH.'/home/index/wall/name/'.$user_name.'" title="'.$user_name.'"><img src="'.WEB_PATH.'/public/timthumb.php?src='.$link.'&w='.$wCrop.'&h='.$hCrop.'&q=100&zc=1"  alt = "'.$name.'" '.$stringOption.'></a>';
        //$return = '<a href="'.WEB_PATH.'/home/index/wall/name/'.$user_name.'" title="'.$user_name.'"><img src="'.$link.'"  alt = "'.$name.'" '.$stringOption.'></a>';
        echo $return;
        return true;
    }
}