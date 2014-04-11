<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/
class HT_Model_administrator_models_utility extends Zend_Db_Table {//ten class fai viet hoa
	const LOG_IMPORT_FILE = 'importLog';
	protected $_db;
	protected $_name;
	public function __construct() {
		$this->_name = "icd10";
		$this->_db = Zend_Registry::get('dbMain');
		require_once('PHPMailer/class.phpmailer.php');
		parent::init();
	}
	
public function renderData($TitleTable=null,$data,$paging = null){
		$ajaxData = null;
		$ajaxData .= "<div class='box bordered-box blue-border' style='margin-bottom:0;'>
				<div class='box-header blue-background'>
                      <div class='title'>
				$TitleTable
				</div>
                      <div class='actions'>
                        <a class='btn box-remove btn-xs btn-link' href='#'><i class='icon-remove'></i>
                        </a>
                        
                        <a class='btn box-collapse btn-xs btn-link' href='#'><i></i>
                        </a>
                      </div>
                    </div>
				<div class='box-content box-no-padding'>
				<div class='responsive-table'>
				<div class='scrollable-area'>";
		$ajaxData .= $data;
		$ajaxData .= '</div></div></div></div></div></div>';
		if($paging){
			$ajaxData .= $paging;
		}
		return $ajaxData;
	}
	
	function render_meta($title,$description){
		$arr= array();
		$title="<title>'.$title.'</title>";
		$des='<meta name="description" content="'.$description.'" />';
		array_push($arr, $title);
		array_push($arr, $$des);
		return $arr ;
	}
	
	function mb_ucfirst($str) {
		$fc = mb_strtoupper(mb_substr($str, 0, 1));
		return $fc.mb_substr($str, 1);
	}
	
	function unzipFile($path,$file){
		$filePath = $path.$file;
		$zip = new ZipArchive;
		$res = $zip->open($filePath);
		if ($res === TRUE) {
			$zip->extractTo($path);
			$zip->close();
			return true;
		} else {
			return false;
		}
	}
	
	public function parseModuleName($url){
		$hasString = strpos($url, 'tin-tuc/');
		if($hasString){
			return 'news_detail';
		}else{
			return 'other';
		}
	}
	
	function isLogin($role = null){
		@$auth = Zend_Auth::getInstance();
		$user = @$auth->getStorage()->read();
		if(@$user->user_name){
			if($role){
				if(@$user->user_name === $role){
					return true;
				}else{
					return false;
				}
			}else{
				return true;
			}
		}
	}
	
	public function checkUserPermission($groupId=0){
		$limited = array('all');
		$permission = array();
		switch($groupId){
			case 1:
			case 2:
				$limited = array();
			break;
			case 3:
				$limited = array('menu_research','download_file','menu_level2','menu_level3');
			break;
			case 4:
				$limited = array('menu_research','download_file','menu_level2','menu_level3','user_low');
			break;
			default:
				// Not member yet
				$limited = array('menu_research','search_function','download_file','menu_level2','menu_level3','user_low');
			break;
		}
		foreach($limited as $item){
			$permission[$item] = 1;
		}
		//print_r($permission);
		return (object)$permission;
	}
	
	public function sendMail($mailfrom, $fromname=null,$emailTo,$subject,$body,$altBody=null,$ccList=null, $bccList=null,$replyTo=null,$fileAttach=null){
		//include("PHPMailer/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
		$mail             = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = "mail.yourdomain.com"; // SMTP server
		$mail->SMTPDebug  = SMTP_DEBUG_MOD;        // enables SMTP debug information (for testing)
		// 1 = errors and messages
		// 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
		$mail->Host       = SMTP_SERVER;      		// sets GMAIL as the SMTP server
		$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
		$mail->Username   = GMAIL_USERNAME;  	   // GMAIL username
		$mail->Password   = GMAIL_PASSWORD;        // GMAIL password
		
		if(!$fromname) $fromname = ADMIN_NAME;
		$mail->SetFrom($mailfrom,$fromname);
		if(is_array($replyTo) && sizeof($replyTo) >0) $mail->AddReplyTo($replyTo['mail'],$replyTo['name']);
		$mail->Subject    = $subject;
		if($altBody) $mail->AltBody = $altBody;
		$mail->MsgHTML($body);	
		$environment = SERVER_ENVIRONMENT;
		if($environment != 'localhost'){	
			$mail->AddAddress($emailTo);
			$ccList = explode(',',$ccList);
			$bccList = explode(',',$bccList);
			foreach((array)$ccList as $cc){
				if($cc){
					$mail->AddCC($cc);
				}
			}
			foreach((array)$bccList as $bcc){
				if($bcc){
					$mail->AddBCC($bcc);
				}
			}
		}else{
			$mail->AddAddress(EMAIL_TEST);
		}
		
		$return = array();		
		$mailInfo = $mailfrom.$fromname.$emailTo.$subject.$body;
		//if($_SESSION['mailInfo'] != $mailInfo  && (int)@$_SESSION['timeSend'] + WAITING_TIME_FOR_SEND_NEXT_EMAIL < time()){
			//$_SESSION['mailInfo'] = $mailInfo;
			//$_SESSION['timeSend'] = time();
			if(!$mail->Send()) {
				//echo "Mailer Error: " . $mail->ErrorInfo;
				$return['errorCode'] = 1;
				$return['errorMessage'] = $mail->ErrorInfo;
			//} else {
				//$return['errorCode'] = 0;
				//$return['errorMessage'] = 'Mail has been sent';
			//}
		}else{
			$return['errorCode'] = 0;
			$return['errorMessage'] = 'Mail has been sent';
		}
		return $return;
	}
	
	function readFiles($dir){
		$fileList = array();
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					$file = $dir."/".$object;
					if(!in_array($file,$fileList)) $fileList[] = $file;
				}
			}
			reset($objects);
		}
		return $fileList;
	}
		
	function deleteFolder($dir) {
	   if (is_dir($dir)) {
	     $objects = scandir($dir);
	     foreach ($objects as $object) {
	       if ($object != "." && $object != "..") {
	         if (filetype($dir."/".$object) == "dir") deleteFolder($dir."/".$object); else unlink($dir."/".$object);
	       }
	     }
	     reset($objects);
	     rmdir($dir);
	   }
	 }
	 
	 function deleteFile($filePath){
	 	@unlink($filePath);
	 }
	
	function createFolder($path,$folderName,$mode='0777'){
		$folderPath = $path.$folderName;
		mkdir($folderPath);
		chmod($folderPath, $mode);
	}
	
	function paging($page, $size, $totalRecord, $orderBy=null, $direction=null, $currentForm=null) {
		$objNavigator = new HT_Model_administrator_models_navigator(1, $size, 8);
		$navigator = $objNavigator->buildNavigator($totalRecord, $page);
	
		$naviData = array();
		$naviData['data'] = $navigator;
		$naviData['orderBy'] = $orderBy;
		$naviData['direction'] = $direction;
	
		$navigator = $objNavigator->renderAjaxNavigatorHTML($naviData, $currentForm);
	
		return $navigator;
	}
	
	Static function formatDateTime($dateTime){
		if($dateTime && $dateTime != '0000-00-00 00:00:00' && $dateTime != '1970-01-01 00:00:00'){
			list($date,$time) = explode(" ", $dateTime);
			list($year,$month,$day) = explode("-", $date);
			return $day."/".$month."/".$year;
		}
	}
	
	function log($message, $logFile = null)
	{
		if (!$logFile) {
			$logFile = $_SERVER['SERVER_NAME'].date(' Y-m-d');
		}
		return error_log(date('[Y-m-d H:i:s] ').$message."\n", 3, JPATH_ROOT.'/logs/'.$logFile);
	}

	function logImport($message)
	{
		return Utility::log($message, self::LOG_IMPORT_FILE);
	}

	function readLogImport()
	{
		return file_get_contents(JPATH_ROOT.'/logs/'.self::LOG_IMPORT_FILE);
	}

	function rotateLogImport()
	{
		$logFile = JPATH_ROOT.'/logs/'.self::LOG_IMPORT_FILE;
		return rename($logFile, $logFile.'-'.date('Y-m-d H:i:s'));
	}

	function echo_($text)
	{
		echo htmlspecialchars($text);
	}

	function echo_br($text)
	{
		echo nl2br(htmlspecialchars($text));
	}

	function echo_wbr($text)
	{
		echo str_replace("\n", '<w:br/>', htmlspecialchars($text));
	}

	function echo_ro($text)
	{
		echo htmlspecialchars(str_replace(array('Ä‚â€žÃ¢â‚¬ï¿½','Ä‚â€žÃ†â€™','Ä‚Ë†Ã‹Å“','Ä‚Ë†Ã¢â€žÂ¢','Ä‚Ë†Ã…Â¡','Ä‚Ë†Ã¢â‚¬Âº'), array('A','a','S','s','T','t'), $text));
	}

	function buildArray(&$from, &$to, $keys)
	{
		if (!is_array($keys)) $keys = array($keys);
		foreach ($keys as $key)
		{
			$to[] = $from[$key];
			unset($from[$key]);
		}
	}

	function parseArray(&$from, &$to, $keys)
	{
		if (!is_array($keys)) $keys = array($keys);
		$i = 0;
		foreach ($keys as $key)
		{
			$to[$key] = $from[$i++];
		}
	}

	/**
	 * Function to invalidate an email address
	 *
	 * @Param  $email
	 * @access public
	 * @author Pentalog Inet - People Centric Prj
	 */
	function invalidateEmails($emails)
	{
		list($email_user) = explode('@', $_SERVER['SERVER_ADMIN']);
		$suffix = '...xpc_com...'.$email_user;
		$emails = (array)$emails;
		foreach ($emails as &$email) {
			$email .= $suffix;
		}
		return $emails;
	}

	
	/**
	 * Function to get site language
	 *
	 * @access public
	 * @author Pentalog Inet - People Centric Prj
	 */
	function getLang()
	{
		$language =& JFactory::getLanguage();
		$lang = $language->getTag();
		//$lang = $lang->_lang;
		$lang = substr($lang,0,2);

		return $lang;
	}

	function getLangFourLetters()
	{
		$language =& JFactory::getLanguage();
		$lang = $language->getTag();
		return $lang;
	}

	public static function validateSiteLanguage($language) {
		$language = substr($language, 0, 2);
		if (!in_array($language, array('en', 'fr', 'ro'))) {
			$language = 'fr';
		}
		return $language;
	}

	function getAge($birthday)
	{
		list($bdate, $bmonth, $byear) = explode('.', $birthday);
		list($date , $month , $year ) = explode('.', date('d.m.Y'));
		if($byear < date('Y') && $byear >0){
			$age = $year - $byear;
			if ($bmonth.$bdate > $month.$date) {
				$age--;
			}
			return $age;
		}else{
			return "";
		}
	}

	function getSecretCode($var_id, $var_id2='', $var_id3='')
	{
		$conf = & JFactory::getConfig();
		$secret_code = $conf->getValue('config.secret');
		 
		$scEnc = md5($secret_code.$var_id.$var_id2.$var_id3);
		return $scEnc;
	}

	public static function getJobContactEmails($jobId, $companyId,$companyEmail='') {
		jimport('xws.contact');
		$contacts = XwsContactClient::xWS_GetListContact($jobId,$companyId,$companyEmail);

		return self::getEmails($contacts);
	}

	public static function getEmails($contacts) {
		$emails = array();
		if (count($contacts)) {
			foreach($contacts as $contact) {
				$emails[] = $contact->contactEmail;
			}
		}
		return $emails;
	}

	public function getFileType($documentName = ''){
		$path_parts = pathinfo($documentName);
		$ext = $path_parts['extension'];
		switch ($ext) {
			case 'pdf':
				return 'pdfdocuments';
			case 'doc':
			case 'docx':
				return 'worddocuments';
			case 'gif':
			case 'jpg':
			case 'jpeg':
			case 'png':
				return 'imgdocuments';
			default:
				return 'defaultdocuments';
		}
	}
	/*
	 *
	* @param: $documentType
	*
	* @return: css class name
	*
	* */
	public function getFileCssClass($documentType = ''){
		switch ($documentType) {
			case 'application/msword':
				return 'worddocuments';
				break;
			case 'image/bmp':
			case 'image/cis-cod':
			case 'image/gif':
			case 'image/ief':
			case 'image/jpeg':
			case 'image/jpeg':
			case 'image/jpeg':
			case 'image/pipeg':
			case 'image/svg+xml':
			case 'image/tiff':
			case 'image/tiff':
			case 'image/x-cmu-raster':
			case 'image/x-cmx':
			case 'image/x-icon':
			case 'image/x-portable-anymap':
			case 'image/x-portable-bitmap':
			case 'image/x-portable-graymap':
			case 'image/x-portable-pixmap':
			case 'image/x-rgb':
			case 'image/x-xbitmap':
			case 'image/x-xpixmap':
			case 'image/x-xwindowdump':
			case 'image/png':
				return 'imgdocuments';
				break;
			case 'image/jpeg':
				return 'imgdocuments';
				break;
			case 'application/pdf':
				return 'pdfdocuments';
				break;
			default:
				return 'defaultdocuments';
				break;
		}
	}

	// Vu add 27/12/2010



	function frenchDateTime($dateTime)
	{
		if (!$dateTime) {
			$dateTime = 'now';
		}
		return date('d.m.Y H:i:s', strtotime($dateTime));
	}

	function frenchDate($date)
	{
		if (!$date) {
			$date = 'now';
		}
		return date('d.m.Y', strtotime($date));
	}

	function formatDate($date,$showNow=true)
	{
		$date = trim($date);
		if (!$date && $showNow) {
			$date = 'now';
		}
		if($date){
			return date('d/m/Y', strtotime($date));
		}else{
			return '';
		}
	}
	
	function parseDate($strDate)
	{
		$strDate = trim($strDate);
		if($strDate){
			return date('d/m/Y', $strDate);
		}else{
			return '';
		}
	}
	
	function normalDate($dateTime){
		if($dateTime != '0000-00-00 00:00:00'){
			return date('d.m.Y', strtotime($dateTime));
		}else{
			return null;
		}		
	}
	
	function reportDate($dateTime){
		return date('d.m', strtotime($dateTime));
	}

	function frenchInterviewDate($dateTime)
	{
		if (!$dateTime) {
			$dateTime = 'now';
		}
		return date('d.m.Y H:i', strtotime($dateTime));
	}

	/**
	 * Function to parse date/time from French format
	 * return date/time in mySql format (yyyy-mm-dd HH:MM:SS)
	 *
	 * @Param  $dateTime (dd.mm.yyyy or dd.mm.yyyy HH:MM:SS)
	 * @access public
	 * @author Pentalog Inet - People Centric Prj
	 */
	function parseFrenchDate($dateTime)
	{
		$dateTime = trim($dateTime);
		if (preg_match('/^(?P<day>\d{2})\.(?P<month>\d{2})\.(?P<year>\d{4})$/', $dateTime, $matches)) {
			return sprintf('%s-%s-%s',$matches['year'], $matches['month'], $matches['day']);
		}
		if (preg_match('/^(?P<day>\d{2})\.(?P<month>\d{2})\.(?P<year>\d{4}) (?P<hour>\d{2}):(?P<minute>\d{2})(:(?P<second>\d{2}))?$/', $dateTime, $matches)) {
			if (!$matches['second']) {
				$matches['second'] = '00';
			}
			return sprintf('%s-%s-%s %s:%s:%s',$matches['year'], $matches['month'], $matches['day'], $matches['hour'], $matches['minute'], $matches['second']);
		}
	}

	function formatDBDate($date,$type='/'){
		list($month,$day,$year) = explode($type,$date);
		return $year.'-'.$month.'-'.$day;
	}

	public function cutstring($str, $maxlength = 30, $strip_tag = true){
		if ($strip_tag) {
			$str = strip_tags($str);
		}
		if (strlen($str) > $maxlength){
			return $this->substr($str, 0 , $maxlength).'...';
		}else{
			return $str;
		}
	}

	/*
	 * cut a string if too long, add a tooltip after cutting
	*
	* @param: string, max lenght
	* @return string
	* 18-4-09
	* */
	public function tooltipString($str, $maxlength = 30, $strip_tag = true){
		if ($strip_tag) {
			$str = strip_tags($str);
		}
		if (strlen($str) > $maxlength){
			return "<span title='$str'>".$this->subString($str,$maxlength).'</span>';
		}else{
			return $str;
		}
	}

	function subString($str, $len, $charset='UTF-8'){
		$str = html_entity_decode($str, ENT_QUOTES, $charset);
		if(mb_strlen($str, $charset)> $len){
			$arr = explode(' ', $str);
			$str = mb_substr($str, 0, $len, $charset);
			$arrRes = explode(' ', $str);
			$last = $arr[count($arrRes)-1];
			unset($arr);
			if(strcasecmp($arrRes[count($arrRes)-1], $last)){
				unset($arrRes[count($arrRes)-1]);
			}
			return implode(' ', $arrRes)."...";
		}
		return $str;
	}

	function timeSpan($startMonth, $startYear, $endMonth, $endYear)
	{
		$startTime = !$startMonth ? $startYear : self::monthName($startMonth) .' '. $startYear;
		$endTime   = !$endMonth   ? $endYear   : self::monthName($endMonth  ) .' '. $endYear;
		if (!$startTime) {
			return '';
		}
		if (!$endTime) {
			$endTime = JText::_('now');
		}
		return $startTime .' - '.$endTime;
	}

	function monthName($month)
	{
		$months = array(
				'January'  ,
				'February' ,
				'March'    ,
				'April'    ,
				'May'      ,
				'June'     ,
				'July'     ,
				'August'   ,
				'September',
				'October'  ,
				'November' ,
				'December' ,
		);
		self::log("month: $month ".$months[$month - 1]);
		return JText::_($months[$month - 1]);
	}

	function fileExtension($filename)
	{
		return strtolower(substr(strrchr($filename, '.'), 1));
	}

	function forceDownload($fileName, $fileContent, $fileDate = null)
	{
		if (!$fileDate) {
			$fileDate = date('Y-m-d H:i:s');
		}
		// required for IE
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}

		// get the file mime type using the file extension
		switch(self::fileExtension($fileName))
		{
			case 'txt': $mime = 'text/plain'; break;
			case 'doc': $mime = 'application/vnd.ms-word'; break;
			case 'pdf': $mime = 'application/pdf'; break;
			case 'zip': $mime = 'application/zip'; break;
			case 'jpeg':
			case 'jpg': $mime = 'image/jpg'; break;
			case 'png': $mime = 'image/png'; break;
			case 'gif': $mime = 'image/gif'; break;
			default:    $mime = 'application/octet-stream';
		}
		header('Pragma: public'); 	// required
		header('Expires: 0');		// no cache
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', strtotime($fileDate)).' GMT');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private',false);
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Type: '.$mime);
		header('Content-Length: '.strlen($fileContent));	// provide file size
		header('Connection: close');
		ob_clean();
		echo $fileContent;
	}

	public static function getFileViaWebDav($pcuserType, $userId, $docFile)
	{
		// use HTTP_WebDAV_Client
		// $path   = self::getAlfrescoWebDavRoot($pcuserId);
		// $buffer = file_get_contents($path . $docFile);
		// if ($buffer === false) {
		// jimport('xpc.utility');
		// Utility::log("func getFileViaWebDav, pcuserId: $pcuserId, docFile: $docFile, path: $path, error file_get_contents");
		// }
		// return $buffer;

		// use class_webdav_client from Christian Juerges <christian.juerges@xwave.ch>
		$wdc = self::_initWebDavClient($pcuserType);
		if (!$wdc) {
			return false;
		}
		$folder = self::getUserFolder($userId);
		$http_status = $wdc->get($wdc->get_root() . $folder .'/'. $docFile, $buffer);
		if ($http_status == 200) {
			jimport('xpc.utility');
			Utility::log("func getFileViaWebDav, pcuserType: $pcuserType, userId: $userId, docFile: $docFile, http_status: $http_status");
		}
		return $buffer;
	}

	public static function putFileViaWebDav($pcuserType, $userId, $contentFile, $docFile)
	{
		// use HTTP_WebDAV_Client
		// $path   = self::getAlfrescoWebDavRoot($pcuserId);
		// if (!is_dir($path)) {
		// $res = mkdir($path, 0777, true);
		// if ($res === false) {
		// jimport('xpc.utility');
		// Utility::log("func getFileViaWebDav, pcuserId: $pcuserId, docFile: $docFile, path: $path, error mkdir");
		// }
		// }
		// $bytes  = file_put_contents($path . $docFile, file_get_contents($contentFile));
		// if ($bytes === false) {
		// jimport('xpc.utility');
		// Utility::log("func getFileViaWebDav, pcuserId: $pcuserId, docFile: $docFile, path: $path, error file_put_contents");
		// }
		// return $bytes;

		// use class_webdav_client from Christian Juerges <christian.juerges@xwave.ch>
		$wdc = self::_initWebDavClient($pcuserType);
		if (!$wdc) {
			return false;
		}
		$folder = self::getUserFolder($userId);
		$http_status = $wdc->mkcol($wdc->get_root() . $folder);
		$counter = 0;
		$content = file_get_contents($contentFile);
		$http_status2 = $wdc->put($wdc->get_root() . $folder .'/'. $docFile, $content);
		if (($http_status2 >= 200) && ($http_status2 <= 204)) {
			$counter++;
		} else {
			jimport('xpc.utility');
			Utility::log("func putFileViaWebDav, pcuserType: $pcuserType, userId: $userId, docFile: $docFile, http_status: $http_status, http_status2: $http_status2");
		}
		return $counter;
	}

	public static function getUserFolder($userId) {
		$folder = intval($userId / 1000);
		return str_pad($folder, 3, '0', STR_PAD_LEFT) .'/'. $userId;
	}

	public static function _initWebDavClient($pcuserType)
	{
		static $wdc;
		// if (!is_object($wdc)) {
		//$pcuserType = substr($pcuserId, 2 , 2);
		$conf =& JFactory::getConfig();
		$url  =  $conf->getValue('AlfrescoWebDav') . $conf->getValue('AlfrescoFolder_'.$pcuserType);
		$user =  $conf->getValue('AlfrescoUsername');
		$pass =  $conf->getValue('AlfrescoPassword');
		$url  =  new JURI($url);

		jimport('xws.webdav_client');
		$wdc = new webdav_client();
		$wdc->set_server($url->getHost());
		$wdc->set_port($url->getPort());
		$wdc->set_user($user);
		$wdc->set_pass($pass);
		$wdc->set_protocol(1); // use HTTP/1.1
		$wdc->set_debug(false);
		$wdc->set_root($url->getPath());
		if (!$wdc->open()) {
			Utility::log("func _initWebDavClient, pcuserType: $pcuserType, cannot open server connection");
			$wdc = null;
			return false;
		}
		// }
		return $wdc;
	}

	public static function _closeWebDavClient($wdc)
	{
		if ($wdc) {
			$wdc->close();
		}
	}

	public function getBookingDomainId($statusId,$feedbackId,$creator)
	{
		$domainId = 0;
		if($statusId == 2 && $creator == "xpc" && !$feedbackId){
			$domainId = 1;
		}else if(
				($statusId ==  3 && (in_array($feedbackId, array(2,3,4))))
				|| (in_array($statusId,array(1,2)) && in_array($feedbackId, array(2,3)))
				|| ($statusId == 4 && $feedbackId == 4)
		){
			$domainId = 2;
		}else if(in_array($statusId, array(1,2,3)) && $feedbackId == 1){
			$domainId = 3;
		}

		return $domainId;
	}

	const E_INVALID_FILE_SIZE = 101; //file size: bigger than maxDocumentSize
	const E_INVALID_FILE_TYPE = 102; //file type: not exist in list validDocumentTypes

	/*
	 * return true if no file uploaded
	*/
	public static function checkUploadFile($uploadFile)
	{
		if ($uploadFile['error'] != UPLOAD_ERR_OK) {
			return $uploadFile['error'];
		}
		if ($uploadFile['size'] > Utility::getCfg('maxDocumentSize')) {
			return UPLOAD_ERR_FORM_SIZE;
		}
		if (!in_array(self::fileExtension($uploadFile['name']), Utility::getCfg('validDocumentTypes'))) {
			return self::E_INVALID_FILE_TYPE;
		}
		return UPLOAD_ERR_OK;
	}

	function getCfg($varname)
	{
		$config =& JFactory::getConfig();
		return $config->getValue($varname);
	}

	public function convertPostForWs($post,$keyname)
	{
		if (!is_array($post) || sizeof($post) == 0) {
			return array();
		}
		$newPost = array();
		foreach ($post as $key=>$value) {
			$newPost["{$keyname}[$key]"] = $value;
		}
		return $newPost;
	}

	public function getDisplayName($candidate, $allowedShowFirstname = false){ // $candidate: Object
		if($allowedShowFirstname){
			$displayName = $candidate->firstName;
			if(isset($candidate->lastName) && $candidate->lastName != ''){
				$displayName .= ' ' . strtoupper(substr($candidate->lastName, 0, 1)) . '. ' . $candidate->pcuserId;
			}
		}
		else{
			$displayName = $candidate->pcuserId;
		}

		return $displayName;
	}

	public function checkCaptcha($code)
	{
		if (!$code) {
			return false;
		}
		jimport('captcha-secureimage.securimage');
		$securimage = new Securimage();
		return $securimage->check($code);
	}

	function GetCombobox($coboboxName,$keyColumn,$valueColumn,$tableName,$filter=array()){
		$defaultValue      =  @$filter['defaultValue'];
		$whereCondition    =  @$filter['where'];
		$groupBy           =  @$filter['groupBy'];
		$orderBy           =  @$filter['orderBy'];
		$isBlankVal        =  @$filter['isBlankVal'];
		$isLinkTable       =  @$filter['isLinkTable'];
		$isMultiple        =  @$filter['isMultiple'];
		$cssClass          =  @$filter['cssClass'];
		$valueColumnAlias  =  @$filter['valueColumnAlias'];
		//$keyColumn 	       =  @$filter['keyColumn'];
		
		$data = $this->Query2Cols($tableName,$keyColumn,$valueColumn,$whereCondition,$groupBy,$orderBy,$isLinkTable);
		if($valueColumnAlias) $valueColumn = $valueColumnAlias;
		return $this->toCombobox($coboboxName,$data,$keyColumn,$valueColumn,$defaultValue,$isBlankVal,$isMultiple,$cssClass);
	}
	
	function Query2Options($tableName,$keyColumn,$valueColumn,$whereCondition,$groupBy,$orderBy,$isLinkTable,$languageId,$defaultValue=null){
		if($defaultValue){
			$selected = explode(',', $defaultValue);
		}else{
			$selected = array();
		}
		$dataList = $this->Query2Cols($tableName,$keyColumn,$valueColumn,$whereCondition,$groupBy,$orderBy,$isLinkTable,$languageId);
		$options = '';
		foreach((array)$dataList as $data){
			if(!in_array($data->$keyColumn,$selected)){
				$options .= '<option value="'.$data->$keyColumn.'">'.$data->$valueColumn.'</option>';
			}else{
				$options .= '<option value="'.$data->$keyColumn.'" selected="selected">'.$data->$valueColumn.'</option>';
			}
		}
		return $options;
	}
	
	function Query2Cols($tableName,$keyColumn,$valueColumn,$whereCondition=null,$groupBy=null,$orderBy=null,$isLinkTable=null){
		/*
		 * This function will be use to query data and fill to combobox
		* If isLinkTable = yes, that mean we will connect between main table and detail table
		* */
		$db = $this->_db;
		$sql = 'SELECT '.$keyColumn.','.$valueColumn;
		$sql .= ' FROM '.$tableName;
		$sql .= ' WHERE 1=1 ';
		if($whereCondition){
			$sql .= ' AND '.$whereCondition;
		}
		if($groupBy){
			$sql .= ' GROUP BY '.$groupBy;
		}
		if($orderBy){
			$sql .= ' ORDER BY '.$orderBy;
		}
		return $db->fetchAll($sql);
	}
	
	public function toCombobox($combo_name,$data,$id_column,$val_column,$default_val=null,$blank_val = "no",$isMultiple=null,$cssClass=null){
		$return = "";
		$multiple = null;
		$cssPlus = null;
		$disable = null;
		$combo_name = trim($combo_name);
		$arrDefaultVal = explode(',',$default_val);
		if($combo_name != ""){
			if($isMultiple){
				$multiple     = ' multiple="multiple" ';
				$combo_name   .= '[]';
			}
			if($cssClass){
				$cssPlus = ' class= "'.$cssClass.'"';
			}
			if(!is_array($data) || sizeof($data) == 0) $disable = ' disabled="disabled" ';
			$return .= '<select id="'.$combo_name.'" name="'.$combo_name.'" '.$disable.$multiple.$cssPlus.'>';
			if($blank_val != "no"){
				$return .= '<option value="">'.$blank_val.'</option>';
			}
			if(is_array($data)){
				for($i=0;$i<sizeof($data);$i++){
					$id 	= $data[$i][$id_column];
					$val 	= $data[$i][$val_column];
					if(!in_array($id,$arrDefaultVal)){
						$return .= '<option value="'.$id.'">'.$val.'</option>';
					}else{
						$return .= '<option selected="selected" value="'.$id.'">'.$val.'</option>';
					}
				}
			}
			$return .= '</select>';
		}
		return $return;
	}

	function uniqueFile($filename)
	{
		$filename     = str_replace(" ","_",$filename);
		$filename     = self::_change_to_no_mark($filename);
		$path_parts   = pathinfo($filename);
		if (!$path_parts['extension']) {
			$path_parts['extension'] = 'jpg';
		}
		return $path_parts['filename'] .'_'. time() .'.'. $path_parts['extension'];
	}

	function _change_to_no_mark($string){
		$string = trim($string);
		$arr1 = array('Ä‚Â¡', 'Ä‚Â ', 'Ã¡ÂºÂ¡', 'Ã¡ÂºÂ£', 'Ä‚Â£', 'Ã„Æ’', 'Ä‚Â¢', 'Ã¡ÂºÂ¯', 'Ã¡ÂºÂ±', 'Ã¡ÂºÂ³', 'Ã¡ÂºÂµ', 'Ã¡ÂºÂ·', 'Ã¡ÂºÂ¥', 'Ã¡ÂºÂ§', 'Ã¡ÂºÂ©', 'Ã¡ÂºÂ«', 'Ã¡ÂºÂ­', 'Ä‚Â¨', 'Ä‚Â©', 'Ã¡ÂºÂ»', 'Ã¡ÂºÂ½', 'Ã¡ÂºÂ¹', 'Ä‚Âª', 'Ã¡Â»ï¿½', 'Ã¡ÂºÂ¿', 'Ã¡Â»Æ’', 'Ã¡Â»â€¦', 'Ã¡Â»â€¡', 'Ä‚Â¬', 'Ä‚Â­', 'Ã¡Â»â€°', 'Ã„Â©', 'Ã¡Â»â€¹', 'Ä‚Â²', 'Ä‚Â³', 'Ã¡Â»ï¿½', 'Ä‚Âµ', 'Ã¡Â»ï¿½', 'Ã†Â¡', 'Ã¡Â»ï¿½', 'Ã¡Â»â€º', 'Ã¡Â»Å¸', 'Ã¡Â»Â¡', 'Ã¡Â»Â£', 'Ä‚Â´', 'Ã¡Â»â€œ', 'Ã¡Â»â€˜', 'Ã¡Â»â€¢', 'Ã¡Â»â€”', 'Ã¡Â»â„¢', 'Ä‚Â¹', 'Ä‚Âº', 'Ã¡Â»Â§', 'Ã…Â©', 'Ã¡Â»Â¥', 'Ã†Â°', 'Ã¡Â»Â«', 'Ã¡Â»Â©', 'Ã¡Â»Â­', 'Ã¡Â»Â¯', 'Ã¡Â»Â±', 'Ã¡Â»Â³', 'Ä‚Â½', 'Ã¡Â»Â·', 'Ã¡Â»Â¹', 'Ã¡Â»Âµ', 'Ä‚ï¿½', 'Ä‚â‚¬', 'Ã¡ÂºÂ ', 'Ã¡ÂºÂ¢', 'Ä‚Æ’', 'Ã„â€š', 'Ä‚â€š', 'Ã¡ÂºÂ®', 'Ã¡ÂºÂ°', 'Ã¡ÂºÂ²', 'Ã¡ÂºÂ´', 'Ã¡ÂºÂ¶', 'Ã¡ÂºÂ¤', 'Ã¡ÂºÂ¦', 'Ã¡ÂºÂ¨', 'Ã¡ÂºÂª', 'Ã¡ÂºÂ¬', 'Ä‚Ë†', 'Ä‚â€°', 'Ã¡ÂºÂº', 'Ã¡ÂºÂ¼', 'Ã¡ÂºÂ¸', 'Ä‚ï¿½', 'Ã¡Â»â‚¬', 'Ã¡ÂºÂ¾', 'Ã¡Â»â€š', 'Ã¡Â»â€ž', 'Ã¡Â»â‚¬', 'Ä‚Å’', 'Ä‚ï¿½', 'Ã¡Â»Ë†', 'Ã„Â¨', 'Ã¡Â»ï¿½', 'Ä‚â€™', 'Ä‚â€œ', 'Ã¡Â»ï¿½', 'Ä‚â€¢', 'Ã¡Â»Å’', 'Ã†Â ', 'Ã¡Â»Å“', 'Ã¡Â»ï¿½', 'Ã¡Â»ï¿½', 'Ã¡Â»Â ', 'Ã¡Â»Â¢', 'Ä‚â€�', 'Ã¡Â»â€™', 'Ã¡Â»ï¿½', 'Ã¡Â»â€�', 'Ã¡Â»â€“', 'Ã¡Â»Ëœ', 'Ä‚â„¢', 'Ä‚ï¿½', 'Ã¡Â»Â¦', 'Ã…Â¨', 'Ã¡Â»Â¤', 'Ã†Â¯', 'Ã¡Â»Âª', 'Ã¡Â»Â¨', 'Ã¡Â»Â¬', 'Ã¡Â»Â®', 'Ã¡Â»Â°', 'Ã¡Â»Â²', 'Ä‚ï¿½', 'Ã¡Â»Â¶', 'Ã¡Â»Â¸', 'Ã¡Â»Â´', 'Ã„â€˜', 'Ã„ï¿½', 'Ä‚ï¿½', 'Ä‚â€º', 'Ä‚â€¹', 'Ä‚ï¿½', 'Ä‚Å“', 'Ã…Â¸', 'Ä‚â€ž', 'Ä‚â€“', 'Ä‚â€¡', 'ÃˆËœ', 'Ãˆï¿½', 'Ä‚Â®', 'Ä‚Â»', 'Ä‚Â«', 'Ä‚Â¯', 'Ä‚Â¼', 'Ä‚Â¿', 'Ä‚Â¤', 'Ä‚Â¶', 'Ä‚Â§', 'Ãˆâ„¢', 'Ãˆâ€º');
		$arr2 = array('a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'Y', 'Y', 'Y', 'Y', 'Y', 'd', 'D', 'I', 'U', 'E', 'I', 'U', 'Y', 'A', 'O', 'C', 'S', 'T', 'i', 'u', 'e', 'i', 'u', 'y', 'a', 'o', 'c', 's', 't');

		$string = str_replace($arr1, $arr2,$string);
		return $string;
	}

	function getDays($defaultVal=null,$blankVal = null,$blankText=null,$comboboxName = null){
		if(!$comboboxName) $comboboxName = 'day';
		$comboxDays .= '<select id="'.$comboboxName.'" name="'.$comboboxName.'">';
		if($blankVal){
			$comboxDays .= '<option value="'.$blankVal.'">'.$blankText.'</option>';
		}
		for($i=1;$i<=31;$i++){
			if($defaultVal == $i){
				$selected = 'selected';
			}else{
				$selected = null;
			}
			if($i < 10) $i = '0'.$i;
				
			$comboxDays .= '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
		}
		$comboxDays .= '</select>';
		return $comboxDays;
	}

	function getMonths($defaultVal=null,$blankVal = null,$blankText=null,$comboboxName = null){
		if(!$comboboxName) $comboboxName = 'month';
		$comboxMonths .= '<select id="'.$comboboxName.'" name="'.$comboboxName.'">';
		if($blankVal){
			$comboxMonths .= '<option value="'.$blankVal.'">'.$blankText.'</option>';
		}
		for($i=1;$i<=12;$i++){
			if($defaultVal == $i){
				$selected = 'selected';
			}else{
				$selected = null;
			}
			if($i < 10) $i = '0'.$i;
			$comboxMonths .= '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
		}
		$comboxMonths .= '</select>';
		return $comboxMonths;
	}

	function getYears($defaultVal=null,$blankVal = null,$blankText=null,$comboboxName = null,$from=2010,$to=2015){
		if(!$comboboxName) $comboboxName = 'years';
		$comboxYears .= '<select id="'.$comboboxName.'" name="'.$comboboxName.'">';
		if($blankVal){
			$comboxYears .= '<option value="'.$blankVal.'">'.$blankText.'</option>';
		}
		for($i=$from;$i<=$to;$i++){
			if($defaultVal == $i){
				$selected = 'selected';
			}else{
				$selected = null;
			}
			$comboxYears .= '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
		}
		$comboxYears .= '</select>';
		return $comboxYears;
	}

	function endcode_confirm_code($email,$salt){
		$md5 = md5($email.$salt);
		return $md5[1].$md5[7].$md5[4].$md5[6].$md5[9].$md5[3].$md5[7];
	}
	function decode_confirm_code($email,$time,$code){
		$salt = $email.$time;
		$md5 = md5($email.$salt);
		$sysCode = $md5[1].$md5[7].$md5[4].$md5[6].$md5[9].$md5[3].$md5[7];
		//echo $sysCode."<br>";
		//echo $code;
		if($sysCode === $code){
			return true;
		}else{
			return false;
		}
	}

	function getDateValid($day,$month,$year,$format='dd/mm/yyyy'){
		$day = (int)$day;
		$month = (int)$month;
		$year = $year;
		if($day && $month && $year){
			if(checkdate($month,$day,$year)){
				switch($format){
					case 'dd/mm/yyyy':
						return $day.'-'.$month.'-'.$year;
						break;
					default:
						return $day.'-'.$month.'-'.$year;
						break;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}

	}
	
	function importKeywords($fileUpload,$path,$maxFileSize,$fileTypeAllow){
		$this->deleteFile($path.'/keywords.xlsx');
		$this->deleteFile($path.'/csv.zip');
		$this->deleteFolder($path.'/csv');
		$fileAttach 		= @$_FILES[$fileUpload];
		$errorCode 			= 0;
		if($fileAttach){
			$fileTypeAllow      = explode(',', $fileTypeAllow);
			$fileTypeUpload 	= substr($fileAttach['name'],strrpos($fileAttach['name'],'.') + 1);
			$fileTypeUpload 	= strtolower($fileTypeUpload);
			if($fileAttach["name"]) {
				if($fileAttach['size'] > $maxFileSize) {
					$errorCode = 3;
				}elseif(!in_array($fileTypeUpload,$fileTypeAllow)) {
					$errorCode = 4;
				}
				if($errorCode == 0) {
					if($fileTypeUpload == 'zip'){
						$fileName = 'csv.zip';
					}else{
						$fileName = 'keywords.xlsx';
					}
					move_uploaded_file($fileAttach["tmp_name"], $path.$fileName);
				}
			}else{
				$errorCode = 2;
			}
		}else{
			$errorCode = 1;
		}
		if($errorCode == 0){
			if($fileTypeUpload == 'zip'){
				$this->unzipFile($path, 'csv.zip');
			}
			return $fileName;
		}else{
			return $errorCode;
		}
	}	
	
	function uploadFileBasic($fileUpload,$path,$maxFileSize,$fileTypeAllow,$fileName=null,$overwrite = false){
		$fileAttach 		= @$_FILES[$fileUpload];
		$errorCode 			= 0;
		if($fileAttach){
			$fileTypeAllow      = explode(',', $fileTypeAllow);
			$fileTypeUpload 	= substr($fileAttach['name'],strrpos($fileAttach['name'],'.') + 1);
			$fileTypeUpload 	= strtolower($fileTypeUpload);
			if($fileAttach["name"]) {
				if($fileAttach['size'] > $maxFileSize) {
					$errorCode = 3;
				}elseif(!in_array($fileTypeUpload,$fileTypeAllow)) {
					$errorCode = 4;
				}
				if($errorCode == 0) {
					if($fileName && $overwrite){
						// Do nothing
					}else{
						if(!$fileName){
							$fileName = time()."_".$fileAttach['name'];
						}else{
							$fileName = time()."_".$fileName;
						}
					}
					$path 	   .= $fileName;						
					move_uploaded_file($fileAttach["tmp_name"], $path);					
				}
			}else{
				$errorCode = 2;
			}
		}else{
			$errorCode = 1;
		}
		if($errorCode == 0){
			return $fileName;
		}else{
			return $errorCode;
		}
	}
	
	public function overWriteFile($filename,$content){
		// Let's make sure the file exists and is writable first.
		if (is_writable($filename)) {
			if(file_put_contents($filename, $content)){
				$message = "done";
			}else{
				$message = "Error when write file";
			}
		} else {
			$message = "The file $filename is not writable";
		}
		return $message;
	}
	
	function uploadFile($fileUpload,$path,$maxFileSize,$fileTypeAllow,$fileName=null,$thumb=array()){
		$fileAttach 		= @$_FILES[$fileUpload];
		$errorCode 			= 0;
		if($fileAttach){
			$fileTypeAllow      = explode(',', $fileTypeAllow);
			$fileTypeUpload 	= substr($fileAttach['name'],strrpos($fileAttach['name'],'.') + 1);
			$fileTypeUpload 	= strtolower($fileTypeUpload);
			if($fileAttach["name"]) {
				if($fileAttach['size'] > $maxFileSize) {
					$errorCode = 3;
				}elseif(!in_array($fileTypeUpload,$fileTypeAllow)) {
					$errorCode = 4;
				}
				if($errorCode == 0) {
					if(!$fileName){
						$fileName = time()."_".$fileAttach['name'];
					}else{
						$fileName = time()."_".$fileName;
					}
					$thumbPath = $path."thumb/".$fileName;
					$path .= $fileName;
					
					move_uploaded_file($fileAttach["tmp_name"], $path);
					if(is_array($thumb) && sizeof($thumb)>0){
						$thumbWidth = $thumb['width'];
						$thumbHeight = $thumb['height'];
						include('SimpleImage.php');
						$image = new SimpleImage();
						$image->load($path);
						$image->resize($thumbWidth,$thumbHeight);
						$image->save($thumbPath);
					}
				}
			}else{
				$errorCode = 2;
			}
		}else{
			$errorCode = 1;
		}
		if($errorCode == 0){
			return $fileName;
		}else{
			return $errorCode;
		}
	}
	
	public function renderCkeditor($name,$height=null,$width=null){
		if(!$height) $height = 300;
		include_once (ROOT_PATH.'/public/ckeditor/ckeditor.php') ;
		require_once (ROOT_PATH.'/public/ckfinder/ckfinder.php') ;
		$ckeditor = new CKEditor() ;
		$ckeditor->basePath    = CK_BASE_PATH.'/public/ckeditor/' ;
		$ckeditor->config['height'] = $height;
		$ckeditor->config['enterMode'] = 'CKEDITOR.ENTER_BR';
		$ckeditor->config['shiftEnterMode'] = 'CKEDITOR.ENTER_P';
		if((int)$width >0) $ckeditor->config['width'] = $width;
		$ckeditor->config['skin'] = 'v2';
		CKFinder::SetupCKEditor( $ckeditor, CK_BASE_PATH.'/public/ckfinder/') ;
		$ckeditor->replace($name);
	}

}
?>
