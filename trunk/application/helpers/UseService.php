<?php
class HT_View_Helper_UseService
{
    public function UseService($user_name, $your_account)
    {
        $modelUser = new HT_Model_user_models_user();
        $userInfo = $modelUser->getUserInfo($user_name);
        $share_service = $userInfo[0]['share_service'];
  
        $modelFriend = new HT_Model_home_models_Friends();
        $checkFriend = $modelFriend->checkFriend($user_name, $your_account);
        
        $modelService = new HT_Model_admin_models_service();
        $currentService = array();
        $currentService = $modelService->currentService($user_name);
        $dataReturn = "";
        if($currentService != NULL){
            $ser_id = $currentService['ser_id'];
            if($ser_id == 2) {
                $dataReturn = '<a href="'.WEB_PATH.'/pregnant" title="'.$user_name.' đang mang thai"><img src="'.WEB_PATH.'/public/img/icon-dangmangthai.png" class="attr-service"/></a>';
            }else{
                $dataReturn = '<a href="'.WEB_PATH.'/pregnant" title="'.$user_name.' chuẩn bị mang thai"><img src="'.WEB_PATH.'/public/img/icon-chuyenbi-mangthai.png" class="attr-service"/></a>';
            }
        }
        switch ($share_service) {
           case 'public':
           echo $dataReturn; 
           break;
           case 'friend':
           if($checkFriend != NULL || $user_name == $your_account){
            echo $dataReturn; 
           }
           break;
           case 'private':
           if($user_name == $your_account){
            echo $dataReturn; 
           }
           break;
           
        }
       
        return true;
    }
}