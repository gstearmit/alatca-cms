<?php
class Administrator_LoginController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$objLogin = new HT_Model_administrator_models_login();
		$do 		= @$this->_request->getParam('do');
		$userid 	= $this->_request->getParam('userid');
		if($do == 'list'){
			$this->getListLogin();
		}elseif($do == 'setActive'){
			$this->setActive();
		}elseif($do == 'checkLoginname'){
			$this->checkExistsLoginname();
		}else{
			$keyword = $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/login/index.js');
	}
	
	public function roleAction(){
		$userid 			= (int)@$this->_request->getParam('userid');
		$do 				= @$this->_request->getParam('do');
		if($do == 'submit'){
			$this->_updateRole();
		}elseif($userid >0){
			$objLogin 		= new HT_Model_administrator_models_login();
			$objUtil 		= new HT_Model_administrator_models_utility();
			
			$user			= $objLogin->getLoginById($userid);
			$role_id		= $user->role_id;
			$role 			= $objUtil->GetCombobox('role_id','role_id','role_name','user_roles',array('defaultValue'=>$role_id,'isBlankVal'=>'Vui lòng chọn nhóm thành viên'));
			
			$this->view->user 	= $user;
			$this->view->role 	= $role;
			$this->view->userid = $userid;
			$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/login/update.js');
		}else{
			$this->_redirect(WEB_PATH.'/administrator/index/denied');
		}
	}
	
	private function _updateRole(){
		$userid 		= (int)@$this->_request->getParam('userid');
		$role_id 		= (int)@$this->_request->getParam('role_id');
		$objLogin 		= new HT_Model_administrator_models_login();
		$data 			= array('role_id'=>$role_id);
		$objLogin->update($data, 'loginid = '.(int)$userid);		
		$this->_redirect(WEB_PATH.'/administrator/login');
	}
	
	public function setActive(){
		$objLogin = new HT_Model_administrator_models_login();
		$userid 	= $this->_request->getParam('userid');
		$active 	= $this->_request->getParam('active');
		$data 		= array('active'=>$active);
		echo $objLogin->update($data,"userid=".(int)$userid); die();
	}
	
	public function checkExistsLoginname(){
		$username 		= $this->_request->getParam('user_name');
		$userid 		= (int)$this->_request->getParam('id');
		$objLogin 		= new HT_Model_administrator_models_login();
		if($userid >0){
			$totalLogin = $objLogin->checkExistsLoginname($username,$userid);
		}else{
			$totalLogin = $objLogin->checkExistsLoginname($username);
		}
		echo $totalLogin; die();
	}
	
	public function updateAction(){
		$objLogin 		= new HT_Model_administrator_models_login();
		$do 		 	= @$this->_request->getParam('do');
		$userid 		= (int)$this->_request->getParam('id');
		$pass 			= $this->_request->getParam('pass');
		$passSave		= md5($pass);
		$status 		= (int)$this->_request->getParam('status');
		//echo $do; die();
		if($do == 'submit'){
			$data = array();
			$data['user_name'] 		= $this->_request->getParam('user_name');
			$data['email'] 			= $this->_request->getParam('email');
			if($userid >0){
				$objLogin->update($data, 'userid='.(int)$userid);
			}else{
				$data['pass'] 		= $passSave;
				$return = $objLogin->addData($data);
				if($return >0){
					$userid = $return;
				}
			}
			
			if($return >0){
				$this->_redirect(WEB_PATH.'/administrator/login');
			}else{
				$this->_redirect(WEB_PATH.'/administrator/login/update?status=1&id='.$userid);
			}
		}elseif($userid >0){
			$this->view->user = $objLogin->getLogin($userid);
		}
		$this->view->userid = $userid;
		$this->view->status 	 = $status;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/login/update.js');
	}

	function deleteLogin($userid){
		/*$objLogin = new HT_Model_administrator_models_login();
		echo $objLogin->deleteUser($userid);die();*/
	}

	function getListLogin(){
		$objUtil 		= new HT_Model_administrator_models_utility();
		$objLogin 		= new HT_Model_administrator_models_login();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$size 			= 10;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		$totalRecord = $objLogin->getListLogin_nb(array('keyword'=>$keyword));
		$listLogin = $objLogin->getListLogin($start,$size,array('keyword'=>$keyword));
		$paging = trim($objUtil->paging($page, $size, $totalRecord));
				
		$ajaxData = '<table cellspacing="0" class="table">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">STT</th>';
				$ajaxData .= '<th width="200">Họ và Tên</th>';
				$ajaxData .= '<th width="200">Nickname</th>';
				$ajaxData .= '<th width="300">Email</th>';
				//$ajaxData .= '<th width="100">Ngày sinh</th>';
				//$ajaxData .= '<th width="100">Giới tính</th>';
				//$ajaxData .= '<th width="100">Avatar</th>';
				$ajaxData .= '<th width="100">Quyền</th>';
				$ajaxData .= '<th width="200"style="white-space: nowrap;" align="center">Điều khiển</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		foreach($listLogin as $user){
			$avatarBox = null;
			$action 	= '<div class="action_buttons">';
			$active 	= $user['active'];
			$sex		= $user['sex'];
			$userid		= $user['userid'];
			$username	= $user['user_name'];
			if($active == 1){
				$action .= '<div id="icon_'.$userid.'" onclick="setActive(\''.$userid.'\',0)" class="icon_on fl"></div>';
			}else{
				$action .= '<div id="icon_'.$userid.'" onclick="setActive(\''.$userid.'\',1)" class="icon_off fl"></div>';
			}
			
			//$action .= '<div onclick="deleteUser(\''.$userid.'\')" class="icon_delete ml5 fl"></div>';
			$action .= '<a  title="Phân quyền" href="'.WEB_PATH.'/administrator/login/role/?userid='.$user['userid'].'"><div class="icon_key ml5 fl"></div></a>';
			$action .= '<a  title="Sửa User" href="'.WEB_PATH.'/administrator/useradmin/role/?userid='.$user['userid'].'"><div class="icon_edit ml5 fl"></div></a>';
			$action .= '<div class="cb"></div>';
			$action .= '</div>';
			$i++;
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$user['userid'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td>'.$user['firstname'].' '.$user['lastname'].'</td>';
			$ajaxData .= '<td>'.$username.'</td>';
			$ajaxData .= '<td>'.$user['email'].'</td>';
			//$ajaxData .= '<td>'.$objUtil->parseDate($user['birthday']).'</td>';
			//$ajaxData .= '<td>'.$sex.'</td>';
			//$ajaxData .= '<td>'.$avatarBox.'</td>';
			$ajaxData .= '<td>'.$user['role_name'].'</td>';
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
			$ajaxData .= $action;
			$ajaxData .= '</td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		
		echo $objUtil->renderData($ajaxData,$paging);die();
	}
}
