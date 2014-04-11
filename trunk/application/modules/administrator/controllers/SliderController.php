<?php
class Administrator_SliderController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$objUtil = new HT_Model_administrator_models_utility();
		$do = @$this->_request->getParam('do');
		$id = (int)$this->_request->getParam('id');
		if($do == 'delete' && $id >0){
			$this->deleteslider($id);
		}elseif($do == 'list'){
			$this->getListslider();
		}else{
			$keyword 				= $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/slider/index.js');
	}
	
	public function updateAction(){
		$objslider 	= new HT_Model_administrator_models_slider();
		$objUtil 	= new HT_Model_administrator_models_utility();
		$do 		= @$this->_request->getParam('do');
		$id 		= (int)$this->_request->getParam('id');
		$status 	= (int)$this->_request->getParam('status');
		$groupId	= null;
		$link_news  = @$this->_request->getParam('link_news');
		
		$del_bg_article_img  = @$this->_request->getParam('del_bg_article_img');
		$del_sil_img_01      = @$this->_request->getParam('del_sil_img_01');
		$del_sil_img_02      = @$this->_request->getParam('del_sil_img_02');
		$del_sil_img_03      = @$this->_request->getParam('del_sil_img_03');
		$del_sil_img_04      = @$this->_request->getParam('del_sil_img_04');
		
		
		
		if($do == 'submit'){
			
			//print_r($_FILES);die();
			$bg_article_img = $objUtil->uploadFile('bg_article_img',NEWS_IMAGE_PATH,MAX_IMAGE_FILE_SIZE,IMAGE_TYPE_ALLOW);
			//echo $bg_article_img;
			//die();
			
			$sil_img_01     = $objUtil->uploadFile('sil_img_01',NEWS_IMAGE_PATH,MAX_IMAGE_FILE_SIZE,IMAGE_TYPE_ALLOW);
			$sil_img_02     = $objUtil->uploadFile('sil_img_02',NEWS_IMAGE_PATH,MAX_IMAGE_FILE_SIZE,IMAGE_TYPE_ALLOW);
			$sil_img_03     = $objUtil->uploadFile('sil_img_03',NEWS_IMAGE_PATH,MAX_IMAGE_FILE_SIZE,IMAGE_TYPE_ALLOW);
			$sil_img_04     = $objUtil->uploadFile('sil_img_04',NEWS_IMAGE_PATH,MAX_IMAGE_FILE_SIZE,IMAGE_TYPE_ALLOW);
			
			
			$data = array();
			if(!in_array($bg_article_img,array(1,2,3,4))){
				$data['bg_article_img'] = $bg_article_img;
			}
			if(!in_array($sil_img_01,array(1,2,3,4))){
				$data['sil_img_01'] = $sil_img_01;
			}
			if(!in_array($sil_img_02,array(1,2,3,4))){
				$data['sil_img_02'] = $sil_img_02;
			}
			if(!in_array($sil_img_03,array(1,2,3,4))){
				$data['sil_img_03'] = $sil_img_03;
			}
			if(!in_array($sil_img_04,array(1,2,3,4))){
				$data['sil_img_04'] = $sil_img_04;
			}
			
			if ( $link_news !=''){
			  $data['link_news'] 		= $link_news ;
		    }
			$data['sil_key'] 		= $this->_request->getParam('sil_key');
			$data['sil_title']   	= $this->_request->getParam('sil_title');
			$data['sil_description'] 	= $this->_request->getParam('sil_description');
			
			if($del_sil_img_01)         $data['sil_img_01'] = null;
			if($del_bg_article_imgmage) $data['bg_article_img'] = null;
			if($del_sil_img_02)         $data['sil_img_02'] = null;
			if($del_sil_img_03)         $data['sil_img_03'] = null;
			if($del_sil_img_04)         $data['sil_img_04'] = null;
			
			if($id >0){
				$status = $objslider->updateData($data,(int)$id);
			}else{
				$status = $objslider->addData($data);
			}

			if($status > 0){
				$this->_redirect(WEB_PATH.'/administrator/slider');
			}else{
				$redirectLink = WEB_PATH."/administrator/slider/update?status=$status";
				if($id >0) $redirectLink .= "&id=$id";
				$this->_redirect($redirectLink);
			}
		}elseif($id >0){
			$slider				= $objslider->getslider($id);
			$this->view->slider 	= $slider;
		}
		
		
		$this->view->id 		= $id;
		$this->view->status 	= $status;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/slider/update.js');
	}

	function deleteslider($id){
		$objslider = new HT_Model_administrator_models_slider();
		echo $objslider->delete("sil_id=".(int)$id);die();
	}

	function getListslider(){
	    
        $objUtil 		= new HT_Model_administrator_models_utility();
		$objslider 		= new HT_Model_administrator_models_slider();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		
		$size 			= PAGING_SIZE;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		
		$filter = array();
		if($keyword) $filter['keyword'] = $keyword;
		
		
		$totalRecord = $objslider->getListslider_nb($filter);
		$listslider = $objslider->getListslider($start,$size,$filter);
		$paging = trim($objUtil->paging($page, $size, $totalRecord));

		$ajaxData = '<table cellspacing="0" class="table">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">STT</th>';
				//$ajaxData .= '<th width="200">Key</th>';
				$ajaxData .= '<th width="200">Title</th>';
				$ajaxData .= '<th width="250">Description</th>';
				$ajaxData .= '<th width="250">Link news url</th>';
				$ajaxData .= '<th width="250">Background article image</th>';
				$ajaxData .= '<th width="250">Images 01 </th>';
				$ajaxData .= '<th width="250">Images 02</th>';
				$ajaxData .= '<th width="250">Images 03</th>';
				$ajaxData .= '<th width="250">Images 04</th>';
				$ajaxData .= '<th width="50">#</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		$arrGroup = array();
		foreach($listslider as $cfg){
			$i++;
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$cfg['sil_id'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			//$ajaxData .= '<td>'.$cfg['sil_key'].'</td>';
			$ajaxData .= '<td>'.$cfg['sil_title'].'</td>';
			$ajaxData .= '<td>'.$objUtil->tooltipString($cfg['sil_description']).'</td>';
			$ajaxData .= '<td>'.$cfg['link_news'].'</td>';
			$ajaxData .= '<td>'.$cfg['bg_article_img'].'</td>';
			$ajaxData .= '<td>'.$cfg['sil_img_01'].'</td>';
			$ajaxData .= '<td>'.$cfg['sil_img_02'].'</td>';
			$ajaxData .= '<td>'.$cfg['sil_img_03'].'</td>';
			$ajaxData .= '<td>'.$cfg['sil_img_04'].'</td>';
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
			$ajaxData .= '<a class="btn btn-xs" href="'.WEB_PATH.'/administrator/slider/update/?id='.$cfg['sil_id'].'" title="Edit"><i class="icon-edit"></i></a> <a class="btn btn-danger btn-xs" href="#" onclick="deleteslider('.$cfg['sil_id'].')"><i class="icon-remove"></i></a>';
			//$ajaxData .= '<a class="btn btn-xs" href="'.WEB_PATH.'/administrator/slider/update/?id='.$cfg['sil_id'].'" title="Edit"><i class="icon-edit"></i></a>'; 
			$ajaxData .= '</td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		$title="slider page";
		echo $objUtil->renderData($title,$ajaxData,$paging);die();
	}
}
