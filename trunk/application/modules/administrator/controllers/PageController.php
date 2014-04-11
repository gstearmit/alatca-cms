<?php
class Administrator_PageController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$do = @$this->_request->getParam('do');
		$id = (int)$this->_request->getParam('id');
		if($do == 'delete' && $id >0){
			$this->deletePage($id);
		}elseif($do == 'list'){
			$this->getListPage();
		}else{
			$keyword 				= $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/page/index.js');
	}
	
	public function detailAction(){
		$objPage = new HT_Model_administrator_models_page();
		$id 		=  trim($this->_request->getParam('id'));
		if($id){
			$page				= $objPage->getPage($id);
			if(is_array($page) && sizeof($page) >0){
				$this->view->page 	= $page;
			}else{
				$this->_redirect(WEB_PATH.'/administrator/page');
			}
		}else{
			$this->_redirect(WEB_PATH.'/administrator/page');
		}
	}

	function deletePage($id){
		$objPage = new HT_Model_administrator_models_page();
		echo $objPage->delete("page_id=".(int)$id);die();
	}

	function getListPage(){
	    
        $objUtil 		= new HT_Model_administrator_models_utility();
		$objPage 		= new HT_Model_administrator_models_page();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$size 			= PAGING_SIZE;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		$totalRecord = $objPage->getListPage_nb(array('keyword'=>$keyword));
		$listPage = $objPage->getListPage($start,$size,array('keyword'=>$keyword));
		$paging = trim($objUtil->paging($page, $size, $totalRecord));

		$ajaxData = '<table cellspacing="0" class="table">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">STT</th>';
				$ajaxData .= '<th width="200">Title</th>';
				$ajaxData .= '<th width="200">Tags</th>';
				$ajaxData .= '<th width="200">Visit</th>';
				$ajaxData .= '<th width="200">Visitor</th>';
				$ajaxData .= '<th width="200" alt="Time on page" title="Time on page">Time</th>';
				$ajaxData .= '<th width="200" alt="Click to Action" title="Click to Action">CTA</th>';
				$ajaxData .= '<th style="white-space: nowrap;padding-right: 5px;" align="center">#</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		$arrGroup = array();
		foreach($listPage as $cfg){
			$i++;
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$cfg['page_id'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td>'.$cfg['title'].'</td>';
			$ajaxData .= '<td>'.$cfg['tags'].'</td>';
			$ajaxData .= '<td>'.$cfg['visit_count'].'</td>';
			$ajaxData .= '<td>'.$cfg['visitor_count'].'</td>';
			$ajaxData .= '<td>'.$cfg['time_on_page'].'</td>';
			$ajaxData .= '<td>'.$cfg['cta_count'].'</td>';
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
			$ajaxData .= '<a href="'.WEB_PATH.'/administrator/page/detail/?id='.$cfg['page_id'].'">View</a>';
			$ajaxData .= '</td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		echo $objUtil->renderData($ajaxData,$paging);die();
	}
}
