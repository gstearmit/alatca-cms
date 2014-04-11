<?php
class Administrator_ActionController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$objAction = new HT_Model_administrator_models_action();
		$do = @$this->_request->getParam('do');
		$action_id = (int)$this->_request->getParam('id');
		if($do == 'delete' && $action_id >0){
			$this->deleteAction($action_id);
		}elseif($do == 'list'){
			$this->getListAction();
		}else{
			$keyword = $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/action/index.js');
	}
	
	public function updateAction(){
		$objAction = new HT_Model_administrator_models_action();
		$objUtil = new HT_Model_administrator_models_utility();
		$do 		 	= @$this->_request->getParam('do');
		$action_id 		= (int)$this->_request->getParam('id');
		$status 		= (int)$this->_request->getParam('status');
		$module_id 		= (int)$this->_request->getParam('module_id');
		if($do == 'submit'){
			$data = array();
			$data['module_id'] 					= $module_id;
			$data['action_name'] 				= $this->_request->getParam('action_name');
			$data['action_name_display'] 		= $this->_request->getParam('action_name_display');
			$data['description'] 				= $this->_request->getParam('description');
			if($action_id >0){
				$objAction->update($data, 'action_id='.(int)$action_id);
			}else{
				$action_id = $objAction->addData($data);
			}
			//$this->_redirect(WEB_PATH.'/administrator/action/update?status=1&id='.$action_id);
			$this->_redirect(WEB_PATH.'/administrator/module');
		}elseif($action_id >0){
			$action 				= $objAction->getAction($action_id);
			$module_id				= (int)$action['module_id'];
			$this->view->action		= $action;
		}
		$module 					= $objUtil->GetCombobox('module_id','module_id','module_name','zend_modules',array('defaultValue'=>$module_id,'isBlankVal'=>'Vui lòng chọn module','orderBy'=>'module_name'));
		
		$this->view->id 		 = $action_id;
		$this->view->status 	 = $status;
		$this->view->module 	 = $module;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/action/update.js');
	}

	function deleteAction($action_id){
		$objAction = new HT_Model_administrator_models_action();
		echo $objAction->delete("id=".(int)$action_id);die();
	}

	function getListAction(){
		$objUtil 		= new HT_Model_administrator_models_utility();
		$objAction 	= new HT_Model_administrator_models_action();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$size 			= PAGING_SIZE;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		$totalRecord = $objAction->getListAction_nb(array('keyword'=>$keyword));
		$listAction = $objAction->getListAction($start,$size,array('keyword'=>$keyword));
		$paging = trim($objUtil->paging($page, $size, $totalRecord));

		$ajaxData = null;
		if($paging){
			$ajaxData .= '<div class="paging_box">';
			$ajaxData .= $paging;
			$ajaxData .= '<div class="cb"></div></div>';
		}
		$ajaxData .= '<table cellspacing="0" class="tablesorter tablesorterBorder">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">STT</th>';
				$ajaxData .= '<th width="150">Module</th>';
				$ajaxData .= '<th width="150">Tên module hiển thị </th>';
				$ajaxData .= '<th width="150">Chức năng</th>';
				$ajaxData .= '<th width="150">Chức năng hiển thị</th>';
				$ajaxData .= '<th width="250">Mô tả chức năng</th>';
				$ajaxData .= '<th style="white-space: nowrap;padding-right: 5px;" align="center">Điều khiển</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		foreach($listAction as $action){
			$i++;
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$action['action_id'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td>'.$action['module_name'].'</td>';
			$ajaxData .= '<td>'.$action['module_name_display'].'</td>';
			$ajaxData .= '<td><a href="'.WEB_PATH.'/administrator/action/update/?id='.$action['action_id'].'">'.$objUtil->tooltipString($action['action_name'],200).'</a></td>';
			$ajaxData .= '<td><a href="'.WEB_PATH.'/administrator/action/update/?id='.$action['action_id'].'">'.$objUtil->tooltipString($action['action_name_display'],200).'</a></td>';
			$ajaxData .= '<td>'.$action['description'].'</td>';
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
			$ajaxData .= '<a href="#" onclick="deleteAction('.$action['action_id'].')">Xóa</a> | <a href="'.WEB_PATH.'/administrator/action/update/?id='.$action['action_id'].'">Sửa</a>';
			$ajaxData .= '</td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		$ajaxData .= '</div>';
		if($paging){
			$ajaxData .= '<div class="paging_box">';
			$ajaxData .= $paging;
			$ajaxData .= '<div class="cb"></div></div>';
		}
		echo $ajaxData; die();
	}
}
