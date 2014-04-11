
<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_url extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "tracking_url";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function getUrlByKey($key){
		$key 		= addslashes(strtolower(trim($key)));
		$sql 		= "SELECT name FROM tracking_url WHERE url = ? ORDER BY id LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}

	public function addData($data){
		
		$this->insert($data);
		return $this->getMaxId();
	
		}
	
	
	public function getMaxId(){
		$sql = "SELECT MAX(id) FROM tracking_url";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getListall($query) {
		$rows= array();
		$rows= $this->_db->fetchAll($query);
		return $rows;
	}
	
	public function updateData($data,$UrlId){
		$UrlKey = $data['url'];
		if(!$this->_checkExistsKey($UrlKey,$UrlId)){
			$this->update($data,'id = '.(int)$UrlId);
			return $UrlId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsKey($key,$UrlId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($UrlId >0){
			$sql 		= "SELECT COUNT('id') FROM tracking_url  WHERE url REGEXP BINARY '$key' AND id <> ".(int)$UrlId;
		}else{
			$sql 		= "SELECT COUNT('id') FROM tracking_url  WHERE url REGEXP BINARY '$key'";
		}
		return $this->_db->fetchOne($sql);
	}
	
	
	public function getUrl($UrlId,$filter = array()) {
		$sql = " SELECT * FROM tracking_url WHERE id= ".(int)$UrlId;
		return $this->_db->fetchRow($sql);
	}
	
	public function getListUrl_nb($filter = array()) {
		$sqlPlus = $this->getListUrl_sqlPlus($filter);
		$sql = "SELECT COUNT(id)
				FROM tracking_url
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListUrl($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListUrl_sqlPlus($filter);
		$sql = "SELECT *
				FROM tracking_url 
				WHERE 1=1 $sqlPlus ORDER BY url ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	public function getListUrlOrtherDomain($Iddomain,$filter= array()){
		$sqlPlus = $this->getListUrl_sqlPlus($filter);
		
		$obj_tracking = new HT_Model_administrator_models_tracking();
		$rows_tracking = array();
		$rows_url=array();
		$arr=array();
		$sql = "SELECT * FROM tracking_url where 1=1" . $sqlPlus;
		$rows_url= $this->_db->fetchAll($sql);
			foreach ($rows_url as $item){
				if($item['domain_id']!=$Iddomain){
					$rows_tracking=$obj_tracking->check_count_sesion_urlrefence($item['id']);
					array_push($arr, array("['id']" => $item['id'],"['url']"=>$item['url'],"['sesion']"=>$rows_tracking));
				}
			}
		return $arr;
	}
	
	function dates_inbetweens($date1, $date2){
		$arr= array();
		$diff = abs(strtotime($date2) - strtotime($date1));
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
		$minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
		$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
	
		array_push($arr, array("['years']" => $years,"['months']"=>$months,"['day']"=>$days,"['hours']"=>$hours,"['minutes']"=>$minutes,"['secounds']"=>$seconds));
	
		//echo "<br />".$years." years, ".$months." months, ".$days." days, ".$hours." hours, ".$minutes." minutes, ".$seconds." seconds";
	
		return $arr ;
	}
	function  count_time($day,$hours,$minutes,$secounds){
		//$minutes=$minutes+78;
		
		$arr= array();
// 		$minutes=$minutes+ ($secounds/60);
// 		$secounds= $secounds%60;
// 		$hours= $hours + ($minutes/60 );
// 		$minutes = $minutes%60;
// 		$day= $day+ ($hours/24);
// 		$hours= $hours%24;
		
		
// 		//echo 62/60;
// 		echo "<br/>";
// 		$day=floor($day);
// 		$hours=floor($hours);
// 		$minutes=floor($minutes);
// 		$secounds=floor($secounds);
		
		array_push($arr, array('day'=>$day,'hours'=>$hours,'minutes'=>$minutes,'secounds'=>$secounds));
		return $arr ;
	}
	public function GetCount_CTA($id_link,$id_link_cta){
		//$id_link="1991";
		$year="";
		$months="";
		$day="";
		$hours="";
		$minutes="";
		$secounds="";
		$id_link_cta="{8D0C9343-87F5-4713-8F48-2F55C797AC78}";
		
		$arr_time=array();
		$row_sesion = array();
		$row_url = array();
		$row_link=array();
		$obj_tracking = new  HT_Model_administrator_models_tracking();
		$querry= "SELECT * FROM `tracking_tracking` WHERE `id_url_refer` ='".$id_link."'";
		$row_sesion=$obj_tracking->getListall($querry);
		$number=0;
		//var_dump($rows);
		foreach ($row_sesion as $items){
			//echo "Id sesion :".$items['session_id']."& ID : ".$items['id'];
			//echo "<br/>";
		
			$querry= "SELECT * FROM `tracking_tracking` WHERE id_url ='".$id_link_cta."'";
			$row_url=$obj_tracking->getListall($querry);
			//echo $row_url[0]['time_start'];
			//echo "<br/>";
			$time_start=$row_url[0]['time_start'];
			
			$time_s =strtotime($time_start);
			$querry= "SELECT * FROM `tracking_tracking` WHERE session_id ='".$items['session_id']."' and id=".$items['id']."";
			$row_url=$obj_tracking->getListall($querry);
			foreach ($row_url as $item){
				//echo $item['time_start'];
				$time_end=$item['time_start'];
				
				$time_e =strtotime($time_end);
				$dates_array=$this->dates_inbetweens($time_start, $time_end);
				//return $dates_array;
				//echo "<br/>";
				
					
				//return $dates_array;
					
				//$year+=$dates_array[0]["['years']"];
			
				$day=$day+$dates_array[0]["['day']"];
				$hours=$hours+$dates_array[0]["['hours']"];
				$minutes=$minutes+ $dates_array[0]["['minutes']"];
				$secounds=$secounds+ $dates_array[0]["['secounds']"];
				
				$arr_time=$this->count_time($day,$hours,$minutes,$secounds);
				//array_push($arr_obj, array('number' => $number,'time'=>$arr_time));
				//echo "<br/>";
				//echo "Time start :".$time_s;
				//echo "<br/>";
				//echo "Time end :".$time_e;
				if($item['id_url_refer']==$id_link && $time_s < $time_e){
					$number++;
				}
				//echo "<br/>";
			}
		}
		$arr_obj=array();
		
		
		array_push($arr_obj, array('number' => $number,'time'=>$arr_time));
		
		return $arr_obj;
	}
	
	private function getListUrl_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		$keyword = addslashes($keyword);
		if($keyword){
			$sqlPlus .= " AND ( url LIKE '%$keyword%' OR name LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	
}

?>
