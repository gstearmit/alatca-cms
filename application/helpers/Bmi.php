<?php
class HT_View_Helper_Bmi
{
    public function Bmi($user_name)
    {
        $modelUserHeight = new HT_Model_home_models_UserHeight();
        $modelUserWeight= new HT_Model_home_models_UserWeight();
        $weight = 0;
        $height = 0;
        $dataLatestHeight = $modelUserHeight->getLatest($user_name);
        $dataLatestWeight = $modelUserWeight->getLatest($user_name);
        
        $height = $dataLatestHeight['height'];
        $weight = $dataLatestWeight['weight'];
        
        $Bmi = round($weight / (($height * $height) / 10000), 2);
        $returnBmi = 0;
        $msg = "";
        if($Bmi >0){
            $returnBmi = $Bmi;
            if ($Bmi < 18.5) {
                                $msg = "Bạn quá gầy";
                            } else if (($Bmi >= 18.5) & ($Bmi < 23)) {
                                    $msg = "Bình thường";
                                } else if (($Bmi >= 23) & ($Bmi < 25)) {
                                        $msg =  "Bạn bị thừa cân";
                                    } else {
                                        $msg = "Bạn bị béo phì";
                            }
        }
        $data['number'] = $returnBmi;
        $data['msg'] = $msg;
        return $data;
    }
}