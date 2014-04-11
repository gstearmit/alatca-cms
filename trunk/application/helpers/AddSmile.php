<?php
class HT_View_Helper_AddSmile
{
    public function AddSmile($content)
    {
        //$content = strtolower($content);
        $content = strip_tags($content, '<br>,<b>,</br>');
        $content = preg_replace('/(<br[^>]*>\s*){2,}/', '<br />', $content);
  
        $strReplace = array(":d", ":)", ":(", ":p", "<3", ">_<", ";)");
        $strImg = array("<img src='/public/img/smile/teeth_smile.gif' alt=':d'/>", "<img src = '/public/img/smile/regular_smile.gif' alt=':)'/>", "<img src = '/public/img/smile/sad_smile.gif' alt=':('/>", "<img src = '/public/img/smile/tounge_smile.gif' alt=':p'/>", "<img src = '/public/img/smile/heart.gif' alt='<3'/>", "<img src = '/public/img/smile/angry_smile.gif' alt='>_<'/>", "<img src = '/public/img/smile/wink_smile.gif' alt=';)'/>");
        
        $stringBadWord = array(
        'địt'=>'đ**',
        'lồn '=>'l** ',
        'dái '=>'d** ',
        'đụ '=>'đ* ',
        'đĩ '=>'đ* ',
        'đồ má'=>'đ* má',
        'chó chết'=>'c** chết',
        'fuck'=>'f***',
        'con mẹ'=>'con m*',
        'con chó'=>'con c**',
        'thằng chó'=>'thằng c**',
        'thằng cha'=>'t**** cha',
        'cặc '=>'c** ',
        'cứt'=>'c**',
        'đái'=>'đ**',
        'ỉa'=>'i*',
        'shit'=>'s***'
        );
        
        $result = str_replace(array_keys($stringBadWord),array_values($stringBadWord),$content);
        $result = str_replace($strReplace,$strImg,$result);
        
        $result = str_replace("<br />"," <br />",$result);
 
        $result = $this->makeLinks($result);
        $result = $this->hashTag($result);
        return ucfirst($result);
    }
    function makeLinks($str) {
        return  preg_replace_callback('/(http:\/\/|www\.)(\S+)/', 'replace_abc', $str);
    }
    public function hashTag($str){
        return  preg_replace_callback('/(#)(\S+)/', 'hashTagFc', $str);
    }
}
    function replace_abc($arrayResult){
        $url = $arrayResult[0];
        if ($arrayResult[1] == 'www.') {
            return '<a href="http://'.$url.'" target="_blank">' .  substring($url) . '</a>';
        } else {
            return '<a href="'.$url.'" target="_blank">' . substring($url) . '</a>';
        }
    }
    function hashTagFc($arrayResult){
        //return '<a href="http://'.WEB_PATH.'/home/index/hashtag/key/'.$arrayResult[0].'" target="_blank">' .$arrayResult[0]. '</a>';
        return '<a>' .$arrayResult[0]. '</a>';
    }
    function substring($str){
        if(strlen($str) > 50){
            $strReturn = substr($str, 0, 50)." ...";
        }else{
            $strReturn = $str;
        }
        return $strReturn;
    }
