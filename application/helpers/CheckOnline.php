<?php
class HT_View_Helper_CheckOnline
{
    public function CheckOnline($user_name)
    {
        $time = time();
        $arrayImportant = array('huongnd','trung55','anhvucnt2','ipad4','linhtk','nguyencongquang','wishfriend','demo','dungna','Cuong','tuannguyen','diepvic','Hotland_house');
        $modelUser = new HT_Model_home_models_User();
        $infoUser = $modelUser->profileUser($user_name);
        $isStatus = "off";
        if($infoUser != NULL){
            $last_time = $infoUser['last_login'];
            if($last_time > $time){
                $isStatus = "on";
            }
        }else{
            $isStatus = "off";    
        }
        if(in_array($user_name, $arrayImportant)){
            $isStatus = "on";    
        }
        if($isStatus == "on"){
            $returnHtml = " <a title='Đang online'><img src='".WEB_PATH."/public/img/metacontact_online.png' width='15'/></a>";
        }else{
            $returnHtml = "";
        }
        return $returnHtml;
    }
}