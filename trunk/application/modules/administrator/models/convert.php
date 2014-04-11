<?php
class HT_Model_administrator_models_convert extends Zend_Db_Table {//ten class fai viet hoa
	protected $_db;
	public function __construct() {
		$this->_name = "seo_page";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
    public function utf82telex()
    { 
        $utf82telex=array(
            'à'=>'af',
            'à'=>'af',
            'á'=>'as',
            'á'=>'as',  
            'ả'=>'ar',
            'ả'=>'ar',
            'ã'=>'ax',
            'ã'=>'ax',
            'ạ'=>'aj',            
            //to hop
            'ạ'=>'aj',
            
            'ă'=>'aw',            
            'ằ'=>'awf',
            'ẁ'=>'wf',            
            'ắ'=>'aws',
            //to hop            
            'ẃ'=> 'ws',
            'ẳ'=>'awr',
            'w̉' => 'wr',
            'ẵ'=>'awx',
            'w̃'=>'wx',
            'ặ'=>'awj',
            'ẉ' =>'wj',
    
            'â'=>'aa',        
            'ầ'=>'aaf',
            'aà'=>'aaf',
            'ấ'=>'aas',
            'aá' =>'aas',
            'ẩ'=>'aar',
            'aả'=> 'aar',
            'ẫ'=>'aax',
            'aã'=>'aax',
            'ậ'=>'aaj',
            'aạ' =>'aaj',
    
            'đ'=>'dd',
    
            'è'=>'ef',
            'è'=>'ef',
            'é'=>'es',
            'é'=>'es',
            'ẻ'=>'er',
            'ẻ'=>'er',
            'ẽ'=>'ex',
            'ẽ'=>'ex',
            'ẹ'=>'ej',
            'ẹ'=>'ej',
    
            'ê'=>'ee',        
            'ề'=>'eef',
            'eè' =>'eef',
            'ế'=>'ees',
            'eé' =>'ees',
            'ể'=>'eer',
            'eẻ' =>'eer',
            'ễ'=>'eex',
            'eẽ' =>'eex',
            'ệ'=>'eej',
            'eẹ' =>'eej',
    
            'ì'=>'if',
            'ì' =>'if',
            'í'=>'is',
            'í'=>'is',
            'ỉ'=>'ir',
            'ỉ'=>'ir',
            'ĩ'=>'ix',
            'ĩ'=>'ix',
            'ị'=>'ij',
            'ị'=>'ij',
    
            'ò'=>'of',
            'ò'=>'of',
            'ó'=>'os',
            'ó'=>'os',
            'ỏ'=>'or',
            'ỏ'=>'or',
            'õ'=>'ox',
            'õ'=>'ox',
            'ọ'=>'oj',
            'ọ'=>'oj',
    
            'ô'=>'oo',        
            'ồ'=>'oof',
            'oò' =>'oof',
            'ố'=>'oos',
            'oó' => 'oos',
            'ổ'=>'oor',
            'oỏ' =>'oor',
            'ỗ'=>'oox',
            'oõ' =>'oox',
            'ộ'=>'ooj',
            'oọ' =>'ooj',
    
            'ơ'=>'ow',        
            'ờ'=>'owf',
            'oẁ' =>'owf',
            'ờ'=>'owf',            
            'ớ'=>'ows',
            'oẃ' =>'ows',
            'ở'=>'owr',
            'ow̉' =>'owr',
            'ỡ'=>'owx',
            'ow̃' =>'owx',
            'ợ'=>'owj',
            'oẉ' =>'owj',
    
            'ù'=>'uf',
            'ù'=>'uf',
            'ú'=>'us',
            'ú'=>'us',
            'ủ'=>'ur',
            'ủ'=>'ur',
            'ũ'=>'ux',
            'ũ'=>'ux',
            'ụ'=>'uj',
            'ụ'=>'uj',
    
            'ư'=>'uw',        
            'ừ'=>'uwf',
            'uẁ' =>'uwf',
            'ứ'=>'uws',
            'uẃ' =>'uws',
            'ử'=>'uwr',
            'uw̉' =>'uwr',
            'ữ'=>'uwx',
            'uw̃' =>'uwx',
            'ự'=>'uwj',
            'uẉ' =>'uwj',
    
            'ỳ'=>'yf',
            'ỳ' =>'yh',
            'ý'=>'ys',
            'ý'=>'ys',
            'ỷ'=>'yr',
            'ỷ'=>'yr',
            'ỹ'=>'yx',
            'ỹ'=>'yx',
            'ỵ'=>'yj',
            'ỵ'=>'yj',
    
            'À'=>'AF',
            'À'=>'AF',
            'Á'=>'AS',
            'Á'=>'AS',
            'Ả'=>'AR',
            'Ả'=>'AR',
            'Ã'=>'AX',
            'Ã'=>'AX',
            'Ạ'=>'AJ',
            'Ạ'=>'AJ',
            
            'Ă'=>'AW',
            'Ằ'=>'AWF',
            'AẀ' =>'AWF',
            'Ắ'=>'AWS',
            'AẂ' => 'AWS',
            'Ẳ'=>'AWR',
            'AW̉' =>'AWR',
            'Ẵ'=>'AWX',
            'AW̃' =>'AWX',
            'Ặ'=>'AWJ',
            'AẈ' =>'AWJ',
    
            'Â'=>'AA',        
            'Ầ'=>'AAF',
            'AÀ' =>'AAF',
            'Ấ'=>'AAS',
            'AÁ' =>'AAS',
            'Ẩ'=>'AAR',
            'AẢ' =>'AAR',
            'Ẫ'=>'AAX',
            'AÃ' =>'AAX',
            'Ậ'=>'AAJ',
            'AẠ' =>'AAJ',
    
            'Đ'=>'DD',
    
            'È'=>'EF',
            'È'=>'EF',
            'É'=>'ES',
            'É'=>'ES',
            'Ẻ'=>'ER',
            'Ẻ'=>'ER',
            'Ẽ'=>'EX',
            'Ẽ'=>'EX',
            'Ẹ'=>'EJ',
            'Ẹ'=>'EJ',
    
            'Ê'=>'EE',        
            'Ề'=>'EEF',
            'EÈ' =>'EEF',
            'Ế'=>'EES',
            'EÉ' =>'EES',
            'Ể'=>'EER',
            'EẺ' =>'EER',
            'Ễ'=>'EEX',
            'EẼ' =>'EEX',
            'Ệ'=>'EEJ',
            'EẸ' =>'EEJ',
    
            'Ì'=>'IF',
            'Ì'=>'IF',
            'Í'=>'IS',
            'Í'=>'IS',
            'Ỉ'=>'IR',
            'Ỉ'=>'IR',
            'Ĩ'=>'IX',
            'Ĩ'=>'IX',
            'Ị'=>'IJ',
            'Ị'=>'IJ',
    
            'Ò'=>'OF',
            'Ò'=>'OF',
            'Ó'=>'OS',
            'Ó'=>'OS',
            'Ỏ'=>'OR',
            'Ỏ'=>'OR',
            'Õ'=>'OX',
            'Õ'=>'OX',
            'Ọ'=>'OJ',
            'Ọ'=>'OJ',
    
            'Ô'=>'OO',        
            'Ồ'=>'OOF',
            'OÒ' =>'OOF',
            'Ố'=>'OOS',
            'OÓ' =>'OOS',
            'Ổ'=>'OOR',
            'OỎ' =>'OOR',
            'Ỗ'=>'OOX',
            'OÕ' =>'OOX',
            'Ộ'=>'OOJ',
            'OỌ' =>'OOJ',
    
            'Ơ'=>'OW',        
            'Ờ'=>'OWF',
            'OẀ' =>'OWF',
            'Ớ'=>'OWS',
            'OẂ'=>'OWS',
            'Ở'=>'OWR',
            'OW̉' =>'OWR',
            'Ỡ'=>'OWX',
            'OW̃' =>'OWX',
            'Ợ'=>'OWJ',
            'OẈ' =>'OWJ',
    
            'Ù'=>'UF',
            'Ù'=>'UF',
            'Ú'=>'US',
            'Ú'=>'US',
            'Ủ'=>'UR',
            'Ủ'=>'UR',
            'Ũ'=>'UX',
            'Ũ'=>'UX',
            'Ụ'=>'UJ',
            'Ụ'=>'UJ',
    
            'Ư'=>'UW',        
            'Ừ'=>'UWF',
            'UẀ' =>'UWS',
            'Ứ'=>'UWS',
            'UẂ' =>'UWS',
            'Ử'=>'UWR',
            'UW̉' =>'UWR',
            'Ữ'=>'UWX',
            'UW̃' =>'UWX',
            'Ự'=>'UWJ',
            'UẈ' =>'UWJ',
    
            'Ỳ'=>'YF',
            'Ỳ'=>'YF',
            'Ý'=>'YS',
            'Ý'=>'YS',
            'Ỷ'=>'YR',
            'Ỷ'=>'YR',
            'Ỹ'=>'YX',
            'Ỹ'=>'YX',
            'Ỵ'=>'YJ',
            'Ỵ'=>'YJ'
        );
        return $utf82telex;
    }
    public function utf82abc()
    { 
        $utf82abc=array(
            'à'=>'a',
            'á'=>'a', 
            'á'=>'a', 
            'ả'=>'a',
            'ã'=>'a',
            'ạ'=>'a',
            
            'ằ'=>'a',
            'ắ'=>'a',
            'ẳ'=>'a',
            'ẵ'=>'a',
            'ặ'=>'a',
            'ă'=>'a',
    
            'ầ'=>'a',
            'ấ'=>'a',
            'ấ' => 'a',
            'ẩ'=>'a',
            'ẫ'=>'a',
            'ậ'=>'a',
            'â'=>'a',
    
            'đ'=>'d',
    
            'è'=>'e',
            'é'=>'e',
            'ẻ'=>'e',
            'ẽ'=>'e',
            'ẹ'=>'e',
                
            'ề'=>'e',
            'ế'=>'e',
            'ể'=>'e',
            'ễ'=>'e',
            'ệ'=>'e',
            'ê'=>'e',
    
            'ì'=>'i',
            'í'=>'i',
            'ỉ'=>'i',
            'ĩ'=>'i',
            'ị'=>'i',
    
            'ò'=>'o',
            'ó'=>'o',
            'ỏ'=>'o',
            'õ'=>'o',
            'ọ'=>'o',
                    
            'ồ'=>'o',
            'ố'=>'o',
            'ố' => 'o',
            'ổ'=>'o',
            'ỗ'=>'o',
            'ộ'=>'o',
            'ô'=>'o',    
                
            'ờ'=>'o',
            'ớ'=>'o',
            'ở'=>'o',
            'ỡ'=>'o',
            'ợ'=>'o',
            'ơ'=>'o',
            
            'ù'=>'u',
            'ú'=>'u',
            'ủ'=>'u',
            'ũ'=>'u',
            'ụ'=>'u',
            
            
            'ừ'=>'u',
            'ứ'=>'u',
            'ử'=>'u',
            'ữ'=>'u',
            'ự'=>'u',
            'ư'=>'u',
    
            'ỳ'=>'y',
            'ý'=>'y',
            'ỷ' => 'y',
            'ỷ'=>'y',
            'ỹ'=>'y',
            'ỵ'=>'y',
    
            'À'=>'A',
            'Á'=>'A',
            'Ả'=>'A',
            'Ã'=>'A',
            'Ạ'=>'A',
            
            'Ằ'=>'A',
            'Ắ'=>'A',
            'Ẳ'=>'A',
            'Ẵ'=>'A',
            'Ặ'=>'A',
            'Ă'=>'A',
    
            'Ầ'=>'A',
            'Ấ'=>'A',
            'Ẩ'=>'A',
            'Ẫ'=>'A',
            'Ậ'=>'A',
            'Â'=>'A',
    
            'Đ'=>'D',
    
            'È'=>'E',
            'É'=>'E',
            'Ẻ'=>'E',
            'Ẽ'=>'E',
            'Ẹ'=>'E',
    
            'Ề'=>'E',
            'Ế'=>'E',
            'Ể'=>'E',
            'Ễ'=>'E',
            'Ệ'=>'E',
            'Ê'=>'E',
    
            'Ì'=>'I',
            'Í'=>'I',
            'Ỉ'=>'I',
            'Ĩ'=>'I',
            'Ị'=>'I',
    
            'Ò'=>'O',
            'Ó'=>'O',
            'Ỏ'=>'O',
            'Õ'=>'O',
            'Ọ'=>'O',
    
            'Ồ'=>'O',
            'Ố'=>'O',
            'Ổ'=>'O',
            'Ỗ'=>'O',
            'Ộ'=>'O',
            'Ô'=>'O',
    
            'Ờ'=>'O',
            'Ớ'=>'O',
            'Ở'=>'O',
            'Ỡ'=>'O',
            'Ợ'=>'O',
            'Ơ'=>'O',    
    
            'Ù'=>'U',
            'Ú'=>'U',
            'Ủ'=>'U',
            'Ũ'=>'U',
            'Ụ'=>'U',
    
            'Ừ'=>'U',
            'Ứ'=>'U',
            'Ử'=>'U',
            'Ữ'=>'U',
            'Ự'=>'U',
            'Ư'=>'U',
    
            'Ỳ'=>'Y',
            'Ý'=>'Y',
            'Ỷ'=>'Y',
            'Ỹ'=>'Y',
            'Ỵ'=>'Y'
        );
        return $utf82abc;
    }
    // ham chuyen tu unicode to hop san dung san
    public function tohop_dungsan()
    {
        $utf82ab = array('à'=>'à',
            'á'=>'á',             
            'ả'=>'ả',
            'ã'=>'ã',
            'ạ'=>'ạ',
            
            'ằ'=>'ằ',
            'ắ'=>'ắ',
            'ẳ'=>'ẳ',
            'ẵ'=>'ẵ',
            'ặ'=>'ặ',
            'ă'=>'ă',
    
            'ầ'=>'ầ',
            'ấ'=>'ấ',            
            'ẩ'=>'ẩ',
            'ẫ'=>'ẫ',
            'ậ'=>'ậ',
            'â'=>'â',
    
            'đ'=>'đ',
    
            'è'=>'è',
            'é'=>'é',
            'ẻ'=>'ẻ',
            'ẽ'=>'ẽ',
            'ẹ'=>'ẹ',
                
            'ề'=>'ề',
            'ế'=>'ế',
            'ể'=>'ể',
            'ễ'=>'ễ',
            'ệ'=>'ệ',
            'ê'=>'ê',
    
            'ì'=>'ì',
            'í'=>'í',
            'ỉ'=>'ỉ',
            'ĩ'=>'ĩ',
            'ị'=>'ị',
    
            'ò'=>'ò',
            'ó'=>'ó',
            'ỏ'=>'ỏ',
            'õ'=>'õ',
            'ọ'=>'ọ',
                    
            'ồ'=>'ồ',
            'ố'=>'ố',            
            'ổ'=>'o',
            'ỗ'=>'ỗ',
            'ộ'=>'ộ',
            'ô'=>'ô',    
                
            'ờ'=>'ờ',
            'ớ'=>'ớ',
            'ở'=>'ở',
            'ỡ'=>'ỡ',
            'ợ'=>'ợ',
            'ơ'=>'ơ',
            
            'ù'=>'ù',
            'ú'=>'ú',
            'ủ'=>'ủ',
            'ũ'=>'ũ',
            'ụ'=>'ụ',
            
            
            'ừ'=>'ừ',
            'ứ'=>'ứ',
            'ử'=>'ử',
            'ữ'=>'ữ',
            'ự'=>'ự',
            'ư'=>'ư',
    
            'ỳ'=>'ỳ',
            'ý'=>'ý',
            'ỷ' => 'ỷ',            
            'ỹ'=>'ỹ',
            'ỵ'=>'ỵ',
    
            'À'=>'À',
            'Á'=>'Á',
            'Ả'=>'Ả',
            'Ã'=>'Ã',
            'Ạ'=>'Ạ',
            
            'Ằ'=>'Ằ',
            'Ắ'=>'Ắ',            
            'Ẵ'=>'Ẵ',
            'Ặ'=>'Ặ',
            'Ă'=>'Ă',
    
            'Ầ'=>'Ầ',
            'Ấ'=>'Ấ',
            'Ẩ'=>'Ẩ',
            'Ẫ'=>'Ẫ',
            'Ậ'=>'Ậ',
            'Â'=>'Â',
    
            'Đ'=>'Đ',
    
            'È'=>'È',
            'É'=>'É',
            'Ẻ'=>'Ẻ',
            'Ẽ'=>'Ẽ',
            'Ẹ'=>'Ẹ',
    
            'Ề'=>'Ề',
            'Ế'=>'Ế',
            'Ể'=>'Ể',
            'Ễ'=>'Ễ',
            'Ệ'=>'Ệ',
            'Ê'=>'Ê',
    
            'Ì'=>'Ì',
            'Í'=>'Í',
            'Ỉ'=>'Ỉ',
            'Ĩ'=>'Ĩ',
            'Ị'=>'Ị',
    
            'Ò'=>'Ò',
            'Ó'=>'Ó',
            'Ỏ'=>'Ỏ',
            'Õ'=>'Õ',
            'Ọ'=>'Ọ',
    
            'Ồ'=>'Ồ',
            'Ố'=>'Ố',
            'Ổ'=>'Ổ',
            'Ỗ'=>'Ỗ',
            'Ộ'=>'Ộ',
            'Ô'=>'Ô',
    
            'Ờ'=>'Ờ',
            'Ớ'=>'Ớ',
            'Ở'=>'Ở',
            'Ỡ'=>'Ỡ',
            'Ợ'=>'Ợ',
            'Ơ'=>'Ơ',    
    
            'Ù'=>'Ù',
            'Ú'=>'Ú',
            'Ủ'=>'Ủ',
            'Ũ'=>'Ũ',
            'Ụ'=>'Ụ',
    
            'Ừ'=>'Ừ',
            'Ứ'=>'Ứ',
            'Ử'=>'Ử',
            'Ữ'=>'Ữ',
            'Ự'=>'Ự',
            'Ư'=>'Ư',
    
            'Ỳ'=>'Ỳ',
            'Ý'=>'Ý',
            'Ỷ'=>'Ỷ',
            'Ỹ'=>'Ỹ',
            'Ỵ'=>'Ỵ');
            return $utf82ab;
    
    }
    /**
    * @desc Ham comvert tieng viet kieu telex utf8
    * @param $str - doan text can convert
    */
    public function convert_utf8_to_telex($str)
    {        
        $utf82telex=$this->utf82telex();
        return str_replace(array_keys($utf82telex),array_values($utf82telex),$str);
    }
    /**
    * @desc Ham comvert tieng viet kieu telex utf8
    * @param $str - doan text can convert
    */
    public function convert_telex_to_utf8($str)
    {
        $utf82telex=$this->utf82telex();
        return str_replace(array_values($utf82telex),array_keys($utf82telex),$str);
    }
    /**
    * @desc Ham comvert tieng viet kieu khong dau dung trong nhan tin sms
    * @param $str - doan text can convert
    */
    public function convert_utf8_to_sms($str)
    {
        $utf82abc=$this->utf82abc();
        return str_replace(array_keys($utf82abc),array_values($utf82abc),$str);
    }
    // ham chuyen doi ve chu Hoa
    // add boi:bangtd
    // ngay cap nhat: 21/07/09
    public function convert_to_upper($str)
    {
        $arr = array(
            'à' => 'À',
            'à'=>'À',
            'ạ' => 'Ạ',
            'ạ'=>'Ạ',
            'á' => 'Á',
            'á'=>'Á',
            'ã' => 'Ã',
            'ã'=> 'Ã',
            'ả' => 'Ả',
            'ả'=>'Ả',
            'ă' => 'Ă',
            'ă'=>'Ă',
            'ắ' => 'Ắ',
            'ắ'=>'Ắ',
            'ằ' => 'Ằ',
            'ằ  ' =>'Ằ',
            'ặ' => 'Ặ',
            'ặ' =>'Ặ',
            'ẳ' => 'Ẳ',
            'ẳ'=>'Ẳ',
            'ẵ' => 'Ẵ',
            'ẵ'=>'Ẵ',
            'â' => 'Â',
            'â' =>'Â',
            'ầ' => 'Ầ',
            'ầ'=>'Ầ',
            'ấ' => 'Ấ',
            'ấ'=>'Ấ',
            'ẩ' => 'Ẩ',
            'ẩ'=>'Ẩ',
            'ẫ' => 'Ẫ',
            'ẫ'=>'Ẫ',
            'ậ' => 'Ậ',
            'ậ'=>'Ậ',
            'đ' => 'Đ',
            'đ' =>'Đ',
            'é' => 'É',
            'é'=>'É',
            'è' => 'È',
            'è'=>'È',
            'ẹ' => 'Ẹ',
            'ẹ'=>'Ẹ',
            'ẻ' => 'Ẻ',
            'ẻ'=>'Ẻ',
            'ẽ' => 'Ẽ',
            'ẽ'=>'Ẽ',
            'ê' => 'Ê',
            'ê' =>'Ê',
            'ế' => 'Ế',
            'ế'=>'Ế',
            'ề' => 'Ề',
            'ề'=>'Ề',
            'ệ' => 'Ệ',
            'ệ'=>'Ệ',
            'ể' => 'Ể',
            'ể'=>'Ể',
            'ễ' => 'Ễ',
            'ễ'=>'Ễ',
            'í' => 'Í',
            'í'=>'Í',
            'ì' => 'Ì',
            'ì'=>'Ì',
            'ị' => 'Ị',
            'ị'=>'Ị',
            'ỉ' => 'Ỉ',
            'ỉ'=>'Ỉ',
            'ĩ' => 'Ĩ',
            'ĩ'=>'Ĩ',
            'ó' => 'Ó',
            'ó'=>'Ó',
            'ò' => 'Ò',
            'ò'=>'Ò',
            'ọ' => 'Ọ',
            'ọ'=>'Ọ',
            'ỏ' => 'Ỏ',
            'ỏ'=>'Ỏ',
            'õ' => 'Õ',
            'õ'=>'Õ',
            'ô' => 'Ô',
            'ô' =>'Ô',
            'ố' => 'Ố',
            'ố'=>'Ố',
            'ồ' => 'Ồ',
            'ồ'=>'Ồ',
            'ộ' => 'Ộ',
            'ộ'=>'Ộ',
            'ổ' => 'Ổ',
            'ổ'=>'Ổ',
            'ỗ' => 'Ỗ',
            'ỗ'=>'Ỗ',
            'ơ' => 'Ơ',
            //TIEP
            'ờ' => 'Ờ',
            'ớ' => 'Ớ',
            'ợ' => 'Ợ',
            'ở' => 'Ở',
            'ỡ' => 'Ỡ',
            'ù' => 'Ù',
            'ú' => 'Ú',
            'ụ' => 'Ụ',
            'ủ' => 'Ủ',
            'ũ' => 'Ũ',
            'ư' => 'Ư',
            'ừ' => 'Ừ',
            'ứ' => 'Ứ',
            'ự' => 'Ự',
            'ử' => 'Ử',
            'ữ' => 'Ữ',
            'ỳ' => 'Ỳ',
            'ý' => 'Ý',
            'ỵ' => 'Ỵ',
            'ỷ' => 'Ỷ',
            'ỹ' => 'Ỹ' 
        
        );
       $str = str_replace(array_keys($arr),array_values($arr),$str);
       return $str;
    }
    // ham xu ly lam hoa cac chu trong tu khoa
    // Nguoi tao: Bangtd
    // Ngay tao: 21/07/09
    public function process_keyword_upper($source,$keyword)
    {        
        $arrKey_upper = explode(' ',$keyword);
        $source_upper = $this->convert_to_upper(strtoupper($source));
        $arrSource_upper = explode(' ',$source_upper);
        $arrSource = explode(' ',$source);
        for($i = 0; $i < count($arrSource_upper); $i++)
        {
           if(in_array(str_replace(array('.',',','!','?',';',':','..',',,'),'',$arrSource_upper[$i]),$arrKey_upper))
            $str .= ' '.$arrSource_upper[$i];
           else
            $str .= ' '.$arrSource[$i]; 
        }
        return trim($str);
    }
	/**
    * @desc Convert utf8 to url string
    * @param $str - doan text can convert
    */
    
    public function utf8_to_url($str)
    {       
        $str = $this->convert_utf8_to_sms($str);
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        while(substr($str,-1,1) =='-'){
        	$str = substr($str,0,-1);
        }
        return $str;
    }
    // ham xu ly chuyen doi unicode to hop san dung san
    // Nguoi tao: Bangtd
    // Ngay tao: 05/08/09
 	public function convert_tohop_dungsan($str)
    {
       $arr = $this->tohop_dungsan();
       //print_r(array_keys($arr));exit;
       $str = str_replace(array_values($arr),array_keys($arr),$str);
       return $str;
    } 
}
?>