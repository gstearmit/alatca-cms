<?php
class Administrator_NoteController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$objUtil = new HT_Model_administrator_models_utility();
		$do = @$this->_request->getParam('do');
		$id = (int)$this->_request->getParam('id');
		if($do == 'delete' && $id >0){
			$this->deleteNote($id);
		}elseif($do == 'list'){
			$this->getListNote();
		}else{
			$keyword 				= $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		
		$this->view->noteGroup = $objUtil->GetCombobox('group_id','group_id','group_name','note_group',array('cssClass'=>'form-control','isBlankVal'=>'no'));
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/note/index.js');
	}
	
	public function updateAction(){
		$objNote 	= new HT_Model_administrator_models_note();
		$objUtil 	= new HT_Model_administrator_models_utility();
		$do 		= @$this->_request->getParam('do');
		$id 		= (int)$this->_request->getParam('id');
		$status 	= (int)$this->_request->getParam('status');
		$groupId	= null;
		if($do == 'submit'){
			$data = array();
			$data['note_key'] 		= strtolower(trim($this->_request->getParam('note_key')));
			$data['note_title'] 	= $this->_request->getParam('note_title');
			$data['description'] 	= $this->_request->getParam('description');
			$data['group_id'] 		= $this->_request->getParam('group_id');
			if($id >0){
				$status = $objNote->updateData($data,(int)$id);
			}else{
				$status = $objNote->addData($data);
			}

			if($status > 0){
				$this->_redirect(WEB_PATH.'/administrator/note');
			}else{
				$redirectLink = WEB_PATH."/administrator/note/update?status=$status";
				if($id >0) $redirectLink .= "&id=$id";
				$this->_redirect($redirectLink);
			}
		}elseif($id >0){
			$note				= $objNote->getNote($id);
			$groupId			= (int)$note['group_id'];
			$this->view->note 	= $note;
		}
		
		$this->view->noteGroup = $objUtil->GetCombobox('group_id','group_id','group_name','note_group',array('cssClass'=>'form-control','isBlankVal'=>'no','defaultValue'=>$groupId));
		
		$this->view->id 		= $id;
		$this->view->status 	= $status;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/note/update.js');
	}

	function deleteNote($id){
		$objNote = new HT_Model_administrator_models_note();
		echo $objNote->delete("note_id=".(int)$id);die();
	}

	function getListNote(){
	    
        $objUtil 		= new HT_Model_administrator_models_utility();
		$objNote 		= new HT_Model_administrator_models_note();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$groupId 		= (int)$this->_request->getParam('group_id');
		$size 			= PAGING_SIZE;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		
		$filter = array();
		if($keyword) $filter['keyword'] = $keyword;
		if($groupId) $filter['group_id'] = $groupId;
		
		$totalRecord = $objNote->getListNote_nb($filter);
		$listNote = $objNote->getListNote($start,$size,$filter);
		$paging = trim($objUtil->paging($page, $size, $totalRecord));

		$ajaxData = '<table cellspacing="0" class="table">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">No</th>';
				$ajaxData .= '<th width="700">Note</th>';
				$ajaxData .= '<th width="50">#</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		$arrGroup = array();
		foreach($listNote as $cfg){
			$i++;
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$cfg['note_id'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td>'.$cfg['note_title'].'</td>';
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
			//$ajaxData .= '<a href="#" onclick="deleteNote('.$cfg['note_id'].')">Xóa</a> | <a href="'.WEB_PATH.'/administrator/note/update/?id='.$cfg['note_id'].'">Sửa</a>';
			$ajaxData .= '<a class="btn btn-xs" href="'.WEB_PATH.'/administrator/note/update/?id='.$cfg['note_id'].'" title="Edit"><i class="icon-edit"></i></a>';
			$ajaxData .= '</td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		$title="Notes";
		echo $objUtil->renderData($title,$ajaxData,$paging);die();
	}
}
