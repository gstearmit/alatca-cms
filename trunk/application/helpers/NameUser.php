<?php
class HT_View_Helper_NameUser
{
    public function NameUser($user_name)
    {
        
        if($user_name == NULL){
           $name = "Admin"; 
        }else{
            $model = new HT_Model_user_models_user();
            $info           = $model->getUserInfo($user_name);
            @$name           = $info[0]['firstname'] . " " . $info[0]['lastname'];
        }
        return $name;
    }
}