<?php
class Administrator_UserController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$objUser = new HT_Model_administrator_models_user();
		$do 		= @$this->_request->getParam('do');
		$userid 	= @$this->_request->getParam('userid');
		if($do == 'list'){
			$this->getListUser();
		}elseif($do == 'setActive'){
			$this->setActive();
		}elseif($do == 'checkUsername'){
			$this->checkExistsUsername();
			}
		elseif($do == 'delete' && $userid > 0){
			$this->deleteUser($userid);		
		}else{
			$keyword = $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/user/index.js');
	}
	
	public function changepassAction(){
		$objUser 		= new HT_Model_administrator_models_user();
		$data= array();
		$password=  @$this->_request->getParam('password');
		$newpassword=  @$this->_request->getParam('newpassword');
		$userid = @$this->_request->getParam('id');
		$status="";
		$passSave=md5($password);
		$newpassSave= md5($newpassword);
		
		if($password!="" && $newpassword !=""){
			
			//$status=$objUser->
			
			$row=$objUser->getUserPassword($userid, $passSave);
			//var_dump($row);
			if($row != null){
				$data['pass'] 		= $newpassSave;
				$status=$objUser->update($data, 'userid='.$userid ,'pass='.$passSave);
				$this->view->message="Change password success !";
			}else{
				//$this->view->message="Password fail !";
				$status=2;
			}
		}
		
		$this->view->status=$status;
		
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/user/changepass.js');
	}
	public function roleAction(){
		$userid 			= (int)@$this->_request->getParam('userid');
		$do 				= @$this->_request->getParam('do');
		if($do == 'submit'){
			$this->_updateRole();
		}elseif($userid >0){
			$objUser 		= new HT_Model_administrator_models_user();
			$objUtil 		= new HT_Model_administrator_models_utility();
			
			$user			= $objUser->getUserById($userid);

			$role_id		= $user->role_id;
			$role 			= $objUtil->GetCombobox('role_id','role_id','role_name','user_roles',array('defaultValue'=>$role_id,'isBlankVal'=>'Vui lòng chọn nhóm thành viên'));
			
			$this->view->user 	= $user;
			$this->view->role 	= $role;
			$this->view->userid = $userid;
			$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/user/update.js');
		}else{
			$this->_redirect(WEB_PATH.'/administrator/index/denied');
		}
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
		$objUser 		= new HT_Model_administrator_models_user();
		$do 		 	= @$this->_request->getParam('do');
		$userid 		= @$this->_request->getParam('id');
		$roleId="";
		//echo $userid;
		//die();
		$pass 			= $this->_request->getParam('pass');
		
		$role_id 		= @$this->_request->getParam('role_id');
		
		$passSave		= md5($pass);
		$status 		= (int)$this->_request->getParam('status');
		//echo $do; die();
		if($do == 'submit'){
			$data = array();
			$data['firstname'] 		= $this->_request->getParam('firstname');
			$data['lastname'] 		= $this->_request->getParam('lastname');
			$data['email'] 			= $this->_request->getParam('email');
			$data['role_id']= $role_id;
			if($userid >0){
				$status=$objUser->update($data, 'userid='.$userid);
			}else{
				$data['user_name'] 		= $this->_request->getParam('user_name');
				$data['pass'] 		= $passSave;
				$status = $objUser->addData($data);
			}
			
			if($status >0){
				$this->_redirect(WEB_PATH.'/administrator/user?status='.$status);
			}else{
				$this->_redirect(WEB_PATH.'/administrator/user/update?status=1&id='.$userid);
			}
		}elseif($userid >0){
			
			$this->view->user= $objUser->getUser_id($userid);
			$user = $objUser->getUserById($userid);
			$roleId= $user->role_id;
			//$this->view->test = "test";
			//$this->view->user = $user;
		}
		
		
		
		
		$objUtil 		= new HT_Model_administrator_models_utility();
		$role 			= $objUtil->GetCombobox('role_id','role_id','role_name','user_roles',array('defaultValue'=>$roleId,'isBlankVal'=>'---Select---'));
		
		@$auth 		= Zend_Auth::getInstance();
		$users 		= @$auth->getStorage()->read();
		if($users->role_name == 'Supper Admin'){
		$this->view->role 	= $role;
		}
		$this->view->userid = $userid;
		$this->view->status 	 = $status;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/user/update.js');
	}

	function deleteUser($userid){
		$objUser = new HT_Model_administrator_models_user();
		echo $objUser->deleteUser($userid);die();
	}

	function getListUser(){
		@$auth 		= Zend_Auth::getInstance();
		$users 		= @$auth->getStorage()->read();
	
		
		
		
		
		$objUtil 		= new HT_Model_administrator_models_utility();
		$objUser 		= new HT_Model_administrator_models_user();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$size 			= 10;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		$totalRecord = $objUser->getListUser_nb(array('keyword'=>$keyword));
		$listUser = $objUser->getListUser($start,$size,array('keyword'=>$keyword));
		$paging = trim($objUtil->paging($page, $size, $totalRecord));
				
		$ajaxData="";
		$ajaxData .= '<table cellspacing="0" class="table">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">No</th>';
				$ajaxData .= '<th width="200">Full name</th>';
				$ajaxData .= '<th width="200">Username</th>';
				$ajaxData .= '<th width="300">Email</th>';
				//$ajaxData .= '<th width="100">Ngày sinh</th>';
				//$ajaxData .= '<th width="100">Giới tính</th>';
				//$ajaxData .= '<th width="100">Avatar</th>';
				$ajaxData .= '<th width="100">Role</th>';
				if($users->role_name == 'Supper Admin'){
				$ajaxData .= '<th width="100"style="white-space: nowrap;" align="center">#</th>';
				}
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		foreach($listUser as $user){
			$avatarBox = null;
			$action 	= '<div class="action_buttons">';
			$active 	= $user['active'];
			$sex		= $user['sex'];
			$userid		= $user['userid'];
			$username	= $user['user_name'];
			if($users->role_name == 'Supper Admin'){
			if($active == 1){
				$action .= '<div id="icon_'.$userid.'" onclick="setActive(\''.$userid.'\',0)" class="icon_on fl" title="status on"></div>';
			}else{
				$action .= '<div id="icon_'.$userid.'" onclick="setActive(\''.$userid.'\',1)" class="icon_off fl" title="status off"></div>';
			}
			
			
			$action .= '<a  href="'.WEB_PATH.'/administrator/user/update/?id='.$user['userid'].'" class="btn btn-xs" title="Edit">
			<i class="icon-pencil"></i>
			<a  href="#" onclick="deleteUser('.$user['userid'].')" class="btn btn-danger btn-xs"  title="Delete">
			<i class=" icon-trash "></i>
			</a>';
			
			}
			$action .= '<div class="cb"></div>';
			$action .= '</div>';
			$i++;
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$user['userid'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td>'.$user['lastname'].' '.$user['firstname'].'</td>';
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
		$title= "Usercontroller";
		echo $objUtil->renderData($title,$ajaxData,$paging);die();
	}
}
