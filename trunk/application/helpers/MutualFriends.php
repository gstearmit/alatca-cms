<?php
class HT_View_Helper_MutualFriends
{
    public function MutualFriends($user_name, $user_name_friend)
    {   
        
        $friendModel = new HT_Model_home_models_Friends();
        $listMyFriend = $friendModel->listFriendOfUser($user_name);
        $arrListUserMyFriend = array();
        foreach($listMyFriend as $vMyFriend):
            $arrListUserMyFriend[] = $vMyFriend['friend_name'];
        endforeach;

        $listYourfFriend = $friendModel->listFriendOfUser($user_name_friend);
        $arrListUserYourFriend = array();
        foreach($listYourfFriend as $vYourFriend):
            $arrListUserYourFriend[] = $vYourFriend['friend_name'];
        endforeach;
        
        $arrResult = array_intersect($arrListUserMyFriend, $arrListUserYourFriend);
     
        $data['data'] = $arrResult;
        $data['total'] = count($arrResult);
        
        if($user_name == $user_name_friend){
        $data['data'] = array();
        $data['total'] = 0;
        }
        return $data;
    }
}