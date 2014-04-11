<?php
class Administrator_ModuleController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$objModule = new HT_Model_administrator_models_module();
		$do = @$this->_request->getParam('do');
		$module_id = (int)$this->_request->getParam('id');
		if($do == 'delete' && $module_id >0){
			$this->deleteModule($module_id);
		}elseif($do == 'list'){
			$this->getListModule();
		}else{
			$keyword = $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/module/index.js');
	}
	
	public function updateAction(){
		$objModule = new HT_Model_administrator_models_module();
		$do 		 = @$this->_request->getParam('do');
		$module_id = (int)$this->_request->getParam('id');
		$status 	= (int)$this->_request->getParam('status');
		//echo $do; die();
		if($do == 'submit'){
			$data = array();
			$data['module_name'] 				= strtolower($this->_request->getParam('module_name'));
			$data['module_name_display'] 		= $this->_request->getParam('module_name_display');
			$data['description'] 				= $this->_request->getParam('description');
			if($module_id >0){
				$objModule->update($data, 'module_id='.(int)$module_id);
			}else{
				$module_id = $objModule->addData($data);
			}
			//$this->_redirect(WEB_PATH.'/administrator/module/update?status=1&id='.$module_id);
			$this->_redirect(WEB_PATH.'/administrator/module/');
			//$this->_redirect(WEB_PATH.'/administrator/module/update');
		}elseif($module_id >0){
			$this->view->module = $objModule->getModule($module_id);
		}
		$this->view->id = $module_id;
		$this->view->status 	 = $status;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/module/update.js');
	}

	function deleteModule($module_id){
		$objModule = new HT_Model_administrator_models_module();
		echo $objModule->delete("module_id=".(int)$module_id);die();
	}

	function getListModule(){
		$objUtil 		= new HT_Model_administrator_models_utility();
		$objModule 	= new HT_Model_administrator_models_module();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$size 			= PAGING_SIZE;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		$totalRecord = $objModule->getListModule_nb(array('keyword'=>$keyword));
		$listModule = $objModule->getListModule($start,$size,array('keyword'=>$keyword));
		//echo "<pre>";print_r($listModule);die();
		$paging = trim($objUtil->paging($page, $size, $totalRecord));
		$ajaxData = '<table cellspacing="0" class="table">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">STT</th>';
				$ajaxData .= '<th width="150">Module</th>';
				$ajaxData .= '<th width="200">Module hiển thị</th>';
				$ajaxData .= '<th width="200">Chức năng</th>';
				$ajaxData .= '<th width="200" style="white-space: nowrap;padding-right: 5px;" align="center">Điều khiển</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		foreach($listModule as $module){
			$i++;
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$actions	= $module['actions'];
			$ajaxData .= '<tr id="'.$module['module_id'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td><a href="'.WEB_PATH.'/administrator/module/update/?id='.$module['module_id'].'">'.$objUtil->tooltipString($module['module_name'],200).'</a></td>';
			$ajaxData .= '<td>'.$module['module_name_display'].'</td>';
			$ajaxData .= '<td>';
				foreach((array)$actions as $action){
					$ajaxData .= '<a href="'.WEB_PATH.'/administrator/action/update/?id='.$action['action_id'].'">'.$action['action_name_display'].'</a><br>';
				}
			$ajaxData .= '</td>';
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
			$ajaxData .= '<a href="#" onclick="deleteModule('.$module['module_id'].')">Xóa</a> | <a href="'.WEB_PATH.'/administrator/module/update/?id='.$module['module_id'].'">Sửa</a> | <a href="'.WEB_PATH.'/administrator/action/update/?module_id='.$module['module_id'].'">Thêm chức năng</a>';
			$ajaxData .= '</td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		echo $objUtil->renderData("Quản lý modules",$ajaxData,$paging);die();
	}
}
