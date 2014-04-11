<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_news extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "news";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}

	public function checkExistsNews($title,$action="insert",$newsId = null){
		$title = strtolower(trim($title));
		$title = addslashes($title);
		if($action == 'insert'){
			$sql = "SELECT id_news FROM news WHERE trim(LOWER(title_vn)) REGEXP BINARY '^$title$' LIMIT 1";
			return  (int)$this->_db->fetchOne($sql);
		}else{
			$sql = "SELECT id_news FROM news WHERE trim(LOWER(title_vn)) REGEXP BINARY '^$title$' AND id_news <> ?  LIMIT 1";
			//echo $sql; die();
			return  (int)$this->_db->fetchOne($sql,array($newsId));
		}
	}


	public function updateCategories($categoryIds,$newsId){
		$this->_name = "link_news_category";
		foreach((array)$categoryIds as $cateId){
			$data = array('id_category'=>$cateId,'id_news'=>$newsId);
			$this->insert($data);
		}
		$this->_name = "news";
	}

	private function deleteLinkNewsCate($newsId){
		$this->_name = "link_news_category";
		$this->delete("id_news=".(int)$newsId);
		$this->_name = "news";
	}

	public function getGroupList(){
		$sql = " SELECT group_id,group_name FROM news_group ORDER BY group_order DESC";
		$groupList = $this->_db->fetchAll($sql);
		for($i=0;$i<sizeof($groupList);$i++){
			$groupList[$i]['categoryList'] = $this->getCategoryList($groupList[$i]['group_id']);
		}
		return $groupList;
	}

	public function getCategoryList($groupId){
		$sql = " SELECT id category_id,category_name FROM category WHERE active = 1 AND group_id = ?";
		return $this->_db->fetchAll($sql,array($groupId));
	}

	public function updateData($data,$newsId){
		$title = trim($data['title_vn']);
		if($title != '' && !$this->checkExistsNews($title,'update',$newsId)){
			$this->update($data, "id_news=".(int)$newsId);
			return $newsId;
		}else{
			return "-1";
		}
	}

	public function addData($data){
		$title = trim($data['title_vn']);
		if($title != '' && !$this->checkExistsNews($title)){
			$this->insert($data);
			return $this->getMaxId();
		}else{
			return "-1";
		}
	}

	public function getMaxId(){
		$sql = "SELECT MAX(id_news) FROM news";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getNews($newsId,$filter = array()) {
		$sql = " SELECT * FROM news WHERE id_news =".(int)$newsId;
		$news = $this->_db->fetchRow($sql);
		$news['categories'] = $this->getNewsCates($news['id_news']);
		return $news;
	}

	public function getNewsCates($newsId){
		$cateList = array();
		$sql = " SELECT id_category FROM link_news_category WHERE id_news =".(int)$newsId;
		$categoryList = $this->_db->fetchAll($sql);
		foreach((array)$categoryList as $cate){
			$cateList[] = $cate['id_category'];
		}
		return $cateList;
	}

	public function getListNews_nb($filter = array()) {
		$sqlPlus 	= $this->getListNews_sqlPlus($filter);
		$sqlJoin	= null;
		$searchFor  = trim(@$filter['search_for']);
		if($searchFor == 'connected'){
			$sqlJoin = ' INNER JOIN news sp ON sp.reference_news_id = news.id_news';
		}
		$sql 		= "SELECT news.id_news
		FROM news $sqlJoin
		WHERE news.status='active' $sqlPlus GROUP BY news.id_news ";
		$idList = $this->_db->fetchAll($sql);
		return sizeof($idList);
	}

	public function getListNews($start=0,$size = 10,$filter = array()) {
		$sqlPlus 	= $this->getListNews_sqlPlus($filter);
		$sqlJoin	= null;
		$searchFor  = trim(@$filter['search_for']);
		if($searchFor == 'connected'){
			$sqlJoin = ' INNER JOIN news sp ON sp.reference_news_id = news.id_news';
		}
		$sql 		= "SELECT news.id_news newsId,news.title_vn newsTitle, news.desc_vn description,news.pictrue, news.created
		FROM news
		$sqlJoin
		WHERE news.status='active' $sqlPlus GROUP BY news.id_news ORDER BY news.created DESC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}

	private function getListNews_sqlPlus($filter){
		$sqlPlus = null;
		foreach((array)$filter as $key => $val){
			$key = trim($key);
			$val = addslashes(trim($val));
			switch($key){
				case 'denied_ids':
					if($val){
						$sqlPlus .= " AND news.id_news NOT IN($val)";
					}
					break;
				case 'keyword':
					if($val){
						$sqlPlus .= " AND (news.title_vn LIKE '%$val%') ";
					}
					break;
			}
		}
		return $sqlPlus;
	}

}

?>
