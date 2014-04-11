<?php
class Administrator_SeoController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$objUser = new HT_Model_administrator_models_user();
		$do 		= @$this->_request->getParam('do');
		
		$userid 	= @$this->_request->getParam('headerId');
		
		if($do == 'list'){
			$this->getListUser();
		}elseif($do == 'setActive'){
			$this->setActive();
		}elseif($do == 'checkUsername'){
			$this->checkExistsUsername();
			}
		elseif($do == 'delete' && $userid > 0){
			$this->deleteSeo($userid);		
		}else{
			$keyword = $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/seo/index.js');
	}
	
	private function _updateRole(){
		$userid 		= (int)@$this->_request->getParam('userid');
		$role_id 		= (int)@$this->_request->getParam('role_id');
		$objUser 		= new HT_Model_administrator_models_user();
		$data 			= array('role_id'=>$role_id);
		$objUser->update($data, 'userid = '.(int)$userid);		
		$this->_redirect(WEB_PATH.'/administrator/user');
	}
	
	public function setActive(){
		$objUser = new HT_Model_administrator_models_user();
		$userid 	= $this->_request->getParam('userid');
		$active 	= $this->_request->getParam('active');
		$data 		= array('active'=>$active);
		echo $objUser->update($data,"userid=".(int)$userid); die();
	}
	
	public function checkExistsUsername(){
		$username 		= $this->_request->getParam('user_name');
		$userid 		= (int)$this->_request->getParam('id');
		$objUser 		= new HT_Model_administrator_models_user();
		if($userid >0){
			$totalUser = $objUser->checkExistsUsername($username,$userid);
		}else{
			$totalUser = $objUser->checkExistsUsername($username);
		}
		echo $totalUser; die();
	}
	
	public function updateAction(){
		
		$objSeo 		= new HT_Model_administrator_models_seo();
		$do 		 	= @$this->_request->getParam('do');
		
		$header_id 		= @$this->_request->getParam('id');
		
		$header_key 			= $this->_request->getParam('header_key');
		$page_name 				= $this->_request->getParam('page_name');
		$page_url 				= $this->_request->getParam('page_url');
		$header_title 			= $this->_request->getParam('tag_title');
		$header_des 			= $this->_request->getParam('tag_description');
		$header_order			= $this->_request->getParam('header_order');
		$header_status 			= $this->_request->getParam('header_status');
		
		
	
		$status 		= (int)$this->_request->getParam('status');
		
		if($do == 'submit'){
			
			
			$data = array();
			
			$data['header_key'] 		= $header_key;
			$data['page_name'] 			= $page_name;
			$data['page_url'] 			= $page_url;
			$data['tag_title'] 			= $header_title;
			$data['tag_description'] 	= $header_des;
			$data['header_order'] 		= $header_order;
			$data['header_status'] 		= $header_status;
			
			
			if($header_id >0){
				$status=$objSeo->updateData($data,$header_id);
			}else{
				$status = $objSeo->addData($data);
			}
			
			if($status >0){
				$this->_redirect(WEB_PATH.'/administrator/seo?status='.$status);
			}else{
				$this->_redirect(WEB_PATH.'/administrator/seo/update?status=1&id='.$userid);
			}
		}elseif($header_id >0){
			$this->view->user= $objSeo->getseo_header_id($header_id);
		}
		
		$this->view->header_id = $header_id;
		$this->view->status 	 = $status;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/seo/update.js');
	}

	function deleteSeo($headerId){
		$objSeo = new HT_Model_administrator_models_seo();
		
		echo $objSeo->deleteseo_header($headerId);die();
	}

	function getListUser(){
			$objUtil 		= new HT_Model_administrator_models_utility();
			$objSeo 		= new HT_Model_administrator_models_seo();
			$keyword 		= trim($this->_request->getParam('keyword'));
			$page 			= (int)$this->_request->getParam('page');
			$size 			= PAGING_SIZE;
			if (!is_numeric($page) || $page <= 0) {
				$page = 1;
			}
			$start = $page * $size - $size;
			$totalRecord = $objSeo->getListseo_header_nb(array('keyword'=>$keyword));
			$listConfig = $objSeo->getListseo_header($start,$size,array('keyword'=>$keyword));
			$paging = trim($objUtil->paging($page, $size, $totalRecord));
		
			$ajaxData = '<table cellspacing="0" class="table">';
			$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
			$ajaxData .= '<th width="15">Stt</th>';
			$ajaxData .= '<th width="100">Page name</th>';
			$ajaxData .= '<th width="300">Title</th>';
			$ajaxData .= '<th width="300">Description</th>';
			$ajaxData .= '<th width="60">Order</th>';
			$ajaxData .= '<th width=50">#</th>';
			$ajaxData .= '</tr>';
			$ajaxData .= '</thead>';
		
			$i=0;
			$arrGroup = array();
			foreach($listConfig as $cfg){
				$i++;
				$trClass = null;
				if($i%2 == 1) $trClass = ' class="altrow"';
				$ajaxData .= '<tr id="'.$cfg['header_id'].'" '.$trClass.'>';
				$ajaxData .= '<td align="center">'.$i.'</td>';
				$ajaxData .= '<td>'.$cfg['page_name'].'</td>';
				$ajaxData .= '<td>'.$cfg['tag_title'].'</td>';
					
				$ajaxData .= '<td>'.$cfg['tag_description'].'</td>';
				$ajaxData .= '<td>'.$cfg['header_order'].'</td>';
				$ajaxData .= '<td style="white-space: nowrap" align="center">';
				$ajaxData .='
			<div class="text-center">
			<a href="'.WEB_PATH.'/administrator/seo/update/?id='.$cfg['header_id'].'" class="btn btn-xs" title="Edit">
			<i class="icon-pencil"></i>
			</a>
			<a  href="#" onclick="deleteSeo('.$cfg['header_id'].')" class="btn btn-danger btn-xs"  title="Delete">
			<i class=" icon-trash "></i>
			</a>
			</div>
			';
				//$ajaxData .= '<a href="'.$cfg['url'].'">Mở</a>';
					
				$ajaxData .= '</td>';
					
					
				$ajaxData .= '</tr>';
			}
			$ajaxData .= '</tbody>';
			$ajaxData .= '</table>';
			$title= "Page S.E.O";
			echo $objUtil->renderData($title,$ajaxData,$paging);die();
		
		
		
		
		
		
		
		
		
		
		
		
	}
}
