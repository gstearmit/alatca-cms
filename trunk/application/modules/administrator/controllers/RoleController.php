<?php
class Administrator_RoleController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$objRole = new HT_Model_administrator_models_role();
		$do = @$this->_request->getParam('do');
		$role_id = (int)$this->_request->getParam('id');
		if($do == 'delete' && $role_id >0){
			$this->deleteRole($role_id);
		}elseif($do == 'list'){
			$this->getListRole();
		}else{
			$keyword = $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/role/index.js');
	}
	
	public function updateAction(){
		$objRole = new HT_Model_administrator_models_role();
		$do 		 = @$this->_request->getParam('do');
		$role_id = (int)$this->_request->getParam('id');
		$status 	= (int)$this->_request->getParam('status');
		//echo $do; die();
		if($do == 'submit'){
			$data = array();
			$data['role_name'] 		= $this->_request->getParam('role_name');
			$data['description'] 	= $this->_request->getParam('description');
			if($role_id >0){
				$objRole->update($data, 'role_id='.(int)$role_id);
			}else{
				$role_id = $objRole->addData($data);
			}
			//$this->_redirect(WEB_PATH.'/administrator/role/update?status=1&id='.$role_id);
			$this->_redirect(WEB_PATH.'/administrator/role');
		}elseif($role_id >0){
			$this->view->role = $objRole->getRole($role_id);
		}
		$this->view->id = $role_id;
		$this->view->status 	 = $status;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/role/update.js');
	}

	function deleteRole($role_id){
		$objRole = new HT_Model_administrator_models_role();
		echo $objRole->delete("role_id=".(int)$role_id);die();
	}

	function getListRole(){
		$objUtil 		= new HT_Model_administrator_models_utility();
		$objRole 	= new HT_Model_administrator_models_role();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$size 			= PAGING_SIZE;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		$totalRecord = $objRole->getListRole_nb(array('keyword'=>$keyword));
		$listRole = $objRole->getListRole($start,$size,array('keyword'=>$keyword));
		$paging = trim($objUtil->paging($page, $size, $totalRecord));
		
		$ajaxData = '<table cellspacing="0" class="table">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">STT</th>';
				$ajaxData .= '<th width="200">Tên nhóm</th>';
				$ajaxData .= '<th width="700">Thành viên trong nhóm</th>';
				$ajaxData .= '<th style="white-space: nowrap;padding-right: 5px;" align="center">Điều khiển</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		foreach($listRole as $role){
			$users = $role['users'];
			$i++;
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$role['role_id'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td><a href="'.WEB_PATH.'/administrator/role/update/?id='.$role['role_id'].'">'.$objUtil->tooltipString($role['role_name'],200).'</a></td>';
			$ajaxData .= '<td>';
				foreach((array)$users as $user){
					$ajaxData .= $user['fullname'].'. '.$user['email'].'. <b style="color:#FF0000">'.$user['username'].'</b><br>';
				}
			$ajaxData .= '</td>';
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
			
			$ajaxData .= '<a  href="#" onclick="deleteRole('.$role['role_id'].')" class="btn btn-danger btn-xs"  title="Delete"><i class=" icon-trash "></i></a>';
			$ajaxData .= '<a href="'.WEB_PATH.'/administrator/role/update/?id='.$role['role_id'].'" class="btn btn-xs" title="Edit"><i class="icon-pencil"></i></a>';
			$ajaxData .= '<a href="'.WEB_PATH.'/administrator/acl/?roleId='.$role['role_id'].'" class="btn btn-xs" title="Cấp quyền truy cập"><i class="icon-key"></i></a>';
			
			$ajaxData .= '</td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		$title="Role";
		echo $objUtil->renderData($title,$ajaxData,$paging);die();
	}
}
