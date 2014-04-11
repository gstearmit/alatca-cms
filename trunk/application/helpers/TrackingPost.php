<?php
class HT_View_Helper_TrackingPost
{
    public function TrackingPost($user_name, $post_id)
    {
        $modelTracking              = new HT_Model_home_models_Tracking();
        $checkTracking              = $modelTracking->fetchRow("user_name = '$user_name' and post_id = '$post_id' and status = 1");
        if($checkTracking != NULL){
            $response = '<span onclick="trangkingPost('.$post_id.', 0)" class="tracking_post tracking-post-'.$post_id.'">không theo dõi nữa</span>';
        }else{
            //$response = '<span><a onclick="trangkingPost('.$post_id.', 1)" class="tracking-post-'.$post_id.'">theo dõi bài này</a></span>';
            $response = "";
        }
        echo $response;
        return true;
    }
}