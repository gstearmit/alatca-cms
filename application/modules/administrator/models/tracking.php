<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class HT_Model_administrator_models_tracking extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;
	
	public function __construct() {
		$this->_name = "tracking_tracking";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function addData($data){
		$this->insert($data);
		return $this->getMaxId();
	
		}

     public function getMaxId(){
		$sql = "SELECT MAX(id) FROM tracking_tracking";
		return  (int)$this->_db->fetchOne($sql);
	 }
	
	 public function getListall($query) {
	 	$rows= array();
	 	$rows= $this->_db->fetchAll($query);
	 	return $rows;
	 }
	 
	 public function getTrackingByKey($key){
	 	$key 		= addslashes(strtolower(trim($key)));
	 	$sql 		= "SELECT brower FROM tracking_tracking WHERE ip_client = ? ORDER BY id LIMIT 1";
	 	return $this->_db->fetchOne($sql,array($key));
	 }
	 
	 
	 public function updateData($data,$TrackingId){
	 	$TrackingKey = $data['ip_client'];
	 	if(!$this->_checkExistsKey($TrackingKey,$TrackingId)){
	 		$this->update($data,'id = '.(int)$TrackingId);
	 		return $TrackingId;
	 	}else{
	 		return "-1";
	 	}
	 }
	 
	 private function _checkExistsKey($key,$TrackingId = null){
	 	$objUtil 	= new HT_Model_administrator_models_utility();
	 	$key 		= addslashes(strtolower($key));
	 	if($TrackingId >0){
	 		$sql 		= "SELECT COUNT('id') FROM tracking_tracking  WHERE ip_client REGEXP BINARY '$key' AND id <> ".(int)$TrackingId;
	 	}else{
	 		$sql 		= "SELECT COUNT('id') FROM tracking_tracking  WHERE ip_client REGEXP BINARY '$key'";
	 	}
	 	return $this->_db->fetchOne($sql);
	 }
	 
	 
	 public function getTracking($TrackingId,$filter = array()) {
	 	$sql = " SELECT * FROM tracking_tracking WHERE id= ".(int)$TrackingId;
	 	return $this->_db->fetchRow($sql);
	 }
	 public function getListTracking_nb($filter = array()) {
	 	$sqlPlus = $this->getListTracking_sqlPlus($filter);
	 	$sql = "SELECT COUNT(id)
	 	FROM tracking_tracking
	 	WHERE 1=1 $sqlPlus";
	 	return $this->_db->fetchOne($sql);
	 }
	 public function getListTracking($start=0,$size = 10,$filter = array()) {
	 	$sqlPlus = $this->getListTracking_sqlPlus($filter);
	 	$sql = "SELECT tracking_tracking.id,	tracking_tracking.ip_client	,tracking_tracking.time_start,	tracking_tracking.time_end,	tracking_tracking.brower,	tracking_visitor.name as name,	tracking_url.url as url,tracking_url.name as name,	tracking_tracking.id_url_refer,	tracking_tracking.cta_position
			 	FROM tracking_tracking 
				 	INNER JOIN tracking_visitor
			        ON tracking_visitor.visitor_id = tracking_tracking.guid
			        INNER JOIN tracking_url
			        ON tracking_url.id = tracking_tracking.id_url
			 	WHERE 1=1 $sqlPlus ORDER BY tracking_tracking.time_start DESC LIMIT $start,$size";
	 	return $this->_db->fetchAll($sql);
	 	}
	 
	 private function getListTracking_sqlPlus($filter){
	 	$sqlPlus = null;
	 	if(isset($filter['keyword'])){
	 		$keyword = addslashes(trim(@$filter['keyword']));
	 		$sqlPlus .= " AND ( tracking_tracking.ip_client LIKE '%$keyword%' OR  tracking_tracking.brower LIKE '%$keyword%' ) ";
	 	}
	 	return $sqlPlus;
	 }
	 
	 
	 public function check_count_sesion_urlrefence($idUrlrefe){
	 	$querry="SELECT COUNT(ID) FROM `tracking_tracking` WHERE `id_url_refer` = '".$idUrlrefe."'";
	 	//return $this->getListall($querry);
	 	return $this->_db->fetchOne($querry);
	 }
	 
	 
	 
	 
	 
	public  function trackings_count_url(){
		//$querry
	}
	
	public function trackings($cookie,$link_referer,$link,$time,$status,$position){
		
		
		
		//return;
		function getVisitorIP() {
			$ip = "0.0.0.0";
			if( ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) && ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} elseif( ( isset( $_SERVER['HTTP_CLIENT_IP'])) && (!empty($_SERVER['HTTP_CLIENT_IP'] ) ) ) {
				$ip = explode(".",$_SERVER['HTTP_CLIENT_IP']);
				$ip = $ip[3].".".$ip[2].".".$ip[1].".".$ip[0];
			} elseif((!isset( $_SERVER['HTTP_X_FORWARDED_FOR'])) || (empty($_SERVER['HTTP_X_FORWARDED_FOR']))) {
				if ((!isset( $_SERVER['HTTP_CLIENT_IP'])) && (empty($_SERVER['HTTP_CLIENT_IP']))) {
					$ip = $_SERVER['REMOTE_ADDR'];
				}
			}
			return $ip;
		}
		
		function getGUID(){
			if (function_exists('com_create_guid')){
				return com_create_guid();
			}else{
				mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
				$charid = strtoupper(md5(uniqid(rand(), true)));
				$hyphen = chr(45);// "-"
				$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				.chr(125);// "}"
				return $uuid;
			}
		}
		function get_domain(){
			if(isset($_SERVER['HTTPS'])){
				$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
			}
			else{
				$protocol = 'http';
			}
			return $protocol . "://" . $_SERVER['HTTP_HOST'];
		}

		
		$_SESSION["ss"]="sesion_default";
		if(isset($_SESSION["name"])){
			//echo "có ss";
			//$_SESSION["ss"]=$_SESSION["name"];
			$_SESSION["ss"]=$_SESSION["name"];
			//echo $_SESSION["ss"];
		}else{
			//echo "ko có ss";
			$_SESSION["name"]=  getGUID();
			$_SESSION["ss"]=$_SESSION["name"];
			//echo "ko !";
		}
		
		$url_check="";
		$query="";

		$url_check ="";
		$query     ="";

		date_default_timezone_set('asia/ho_chi_minh');
		$brower    =$_SERVER['HTTP_USER_AGENT'];
		$time_start = date('Y-m-d H:i:s');
		$guid     = getGUID();
		$sIp      = getVisitorIP();
		$link_url = $link;
		$domain   = get_domain();
		$expire   = time()+60*60*24*30;
		//setcookie("user_connect", "", $expire);
		echo "<br/>";
		echo "DOMAIN : ".$domain;
		echo "<br/>";
		$val_guid ="";
	
		$id_url ="";
		$id_url_refer ="";
		$id_domain ="";
		
		//kiểm tra brower có tồn tại cookie không
		
			$val_cookie=$cookie;
			$val_guid=$val_cookie;;
			$obj_visitor= new HT_Model_administrator_models_visitor();
			$sql = "SELECT * FROM `tracking_visitor` WHERE `visitor_id` = '".$val_cookie."'";
			
			
			
			$rows = $obj_visitor->getListall($sql);
			
			
			
			
			
			$rows_domain= array();
			$obj_domain= new HT_Model_administrator_models_domain();
			$sql="SELECT * FROM `tracking_domain` WHERE `domain`= '".$domain."'";
			$rows_domain= $obj_domain->getListall($sql);
				
	
			
			// Kiểm tra domain đã tồn tại hay chưa
					if($rows_domain!=null){
						$id_domain= $rows_domain[0]['domain_id'];
					}else{
						//thêm domain mới
						$data= array();
						$obj_domain= new HT_Model_administrator_models_domain();
						$data['domain_name']="Name update...";
						$data['domain']=$domain;
						$data['description']="Des update...";
						$obj_domain->addData($data);
						//return;
					}
			
			
			$rows_url = array ();
			$obj_url = new HT_Model_administrator_models_url();
			$sql="SELECT * FROM `tracking_url` WHERE `url` = '".$link."' and domain_id = '".$id_domain."'";
			$rows_url = $obj_url->getListall($sql);
		
			
			// kiểm tra url hiện tại và domain có tồn tại hay không
					if($rows_url!=null){
						$id_url=$rows_url[0]['id'];
						echo "Có url :".$id_url;
					}else{
						$data= array();
						$obj_url= new HT_Model_administrator_models_url();
						$data['id']=$guid;
						$data['domain_id']=$id_domain;
						$data['url']=$link;
						$data['name']="test name url";
						$obj_url->addData($data);
						echo " chưa có url";
						return;
					}
			
			
			//return;
			
			$rows_url_refer = array ();
			$sql="SELECT * FROM `tracking_url` WHERE `url` = '".$link_referer."' and domain_id = '".$id_domain."'";
			$rows_url_refer = $obj_url->getListall($sql); //get_item($sql);
			
			// kiểm tra url referrent và domain có tồn tại hay không
				if($rows_url_refer!=null){
					$id_url_refer=$rows_url_refer[0]['id'];
					echo "<br/>";
					echo "Có url refer :".$id_url_refer;
					//echo "ID url_refer :".$rows_url_refer[0]['id'];
				}else{
					//echo "khong co 2";
					$data= array();
					$obj_url= new HT_Model_administrator_models_url();
					$data['id']=$guid;
					$data['domain_id']=$id_domain;
					$data['url']=$link_referer;
					$data['name']="test name url refer";
					$obj_url->addData($data);
					echo " chưa có url reffer";
					return;
					//$url= new Url;
					//$url->id=$guid;
					//$url->url=$link_referer;
					//$url->name="test name";
					//$url->save();
				}
			if($rows!=null){
						if($status=="2"){
							/*
							 $id_guid=$rows[0]['guid'];
							$connect = Yii::app()->db;
							$sql="UPDATE `tbl_tracking` SET  `time_end` =  '".$time_start."' WHERE  `tbl_tracking`.`time_start` =
							'".$time."' and id_url = '".$id_url. "' and id_url_refer = '".$id_url_refer ."' and brower = '".$brower ."' and guid = '" .$id_guid."'";
							$command= $connect->createCommand($sql);
							$command->execute();
							*/
							 
							$id_guid=$rows[0]['visitor_id'];
							$data= array();
							$obj_retracking= new HT_Model_administrator_models_retracking();
							$data['ip_client']=$sIp;
							//$data['domain']=$id_domain;
							$data['time_start']=$time;
							$data['time_end']=$time_start;
							$data['brower']=$brower;
							$data['guid']=$id_guid;
							$data['id_url']=$id_url;
							$data['id_url_refer']=$id_url_refer;
							$obj_retracking->addData($data);
							echo "retracking ............";
							/*
							$trackings= new Retracking;
							$trackings->ip_client=$sIp;
							$trackings->domain=$domain;
							$trackings->time_start = $time;
							$trackings->time_end = $time_start;
							$trackings->brower=$brower;
							$trackings->guid=$id_guid;
							$trackings->id_url=$id_url;
							$trackings->id_url_refer=$id_url_refer;
							$trackings->save();
							*/
						}else{
							$id_guid=$rows[0]['visitor_id'];
							//$trackings = new Tracking;s
							$data = array();
							$obj_tracking= new HT_Model_administrator_models_tracking();
							
							
							echo "<br/>--------------------------------------";
							echo "ID url :".$id_url;
							echo "<br/>--------------------------------------";
							//return;
							$data['ip_client']=$sIp;
							//$data['domain']=$id_domain;
							$data['time_start']=$time;
							$data['brower']=$brower;
							
							$data['guid']=$id_guid;
							
							$data['cta_position']=$position;
							$data['id_url']=$id_url;
							$data['id_url_refer']=$id_url_refer;
							$data['session_id']=$_SESSION["ss"];
							$obj_tracking->addData($data);
							echo "trachking";
							
							/*
							$trackings->ip_client=$sIp;
							$trackings->domain=$domain;
							$trackings->time_start = $time;
							$trackings->brower=$brower;
							$trackings->guid=$id_guid;
							$trackings->id_url=$id_url;
							$trackings->id_url_refer=$id_url_refer;
							$trackings->save();
							*/
						}
			}else{
				$val_guid=$cookie;
				//setcookie("b", $val_guid, $expire);
				$data = array();
				$obj_visitor= new HT_Model_administrator_models_visitor();
				$data['name']="Visitor a";
				$data['visitor_id']=$cookie;
				$obj_visitor->addData($data);
				/*
				$visitor= new Visitor;
				$visitor->name="visitor";
				$visitor->guid=$val_guid;
				$visitor->save();
				*/
			}
			
			/*
	}else{
		// trường hợp Không có cookie
		
			$rows= array();
			$obj_vivitor= new HT_Model_administrator_models_visitor();
			$query = "SELECT count(id) as count FROM `tracking_retracking`";
			$rows=$obj_vivitor->getListall($query);
			
			$val_guid=$guid;
			setcookie("b", $val_guid, $expire);
			$data = array();
			$obj_visitor= new HT_Model_administrator_models_visitor();
			$data['name']="Visitor b";
			$data['visitor_id']=$val_guid;
			$obj_visitor->addData($data);

		}
		*/
	
	}
 
    
}

?>
