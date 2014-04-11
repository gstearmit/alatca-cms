<?php
class Administrator_NewsgroupController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$do = @$this->_request->getParam('do');
		$id = (int)$this->_request->getParam('id');
		if($do == 'delete' && $id >0){
			$this->deleteNewsgroup($id);
		}elseif($do == 'list'){
			$this->getListNewsgroup();
		}else{
			$keyword 				= $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/newsgroup/index.js');
	}
	
	public function updateAction(){
		$objNewsgroup = new HT_Model_administrator_models_newsgroup();
		$do 		 = @$this->_request->getParam('do');
		$id 		= (int)$this->_request->getParam('id');
		$status 	= (int)$this->_request->getParam('status');
		if($do == 'submit'){
			$data = array();
			$data['group_name'] 		= trim($this->_request->getParam('group_name'));
			$data['group_order'] 		= $this->_request->getParam('group_order');
			$data['description'] 		= $this->_request->getParam('description');
			if($id >0){
				$status = $objNewsgroup->updateData($data,(int)$id);
			}else{
				$status = $objNewsgroup->addData($data);
			}

			if($status > 0){
				$this->_redirect(WEB_PATH.'/administrator/newsgroup');
			}else{
				$redirectLink = WEB_PATH."/administrator/newsgroup/update?status=$status";
				if($id >0) $redirectLink .= "&id=$id";
				$this->_redirect($redirectLink);
			}
		}elseif($id >0){
			$newsgroup				= $objNewsgroup->getNewsgroup($id);
			$this->view->newsgroup = $newsgroup;
		}
		$this->view->id 		= $id;
		$this->view->status 	= $status;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/newsgroup/update.js');
	}

	function deleteNewsgroup($id){
		$objNewsgroup = new HT_Model_administrator_models_newsgroup();
		echo $objNewsgroup->delete("group_id=".(int)$id);die();
	}

	function getListNewsgroup(){
		$ajaxData = null;
        $objUtil 		= new HT_Model_administrator_models_utility();
		$objNewsgroup 		= new HT_Model_administrator_models_newsgroup();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$size 			= PAGING_SIZE;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		$totalRecord = $objNewsgroup->getListNewsgroup_nb(array('keyword'=>$keyword));
		$listNewsgroup = $objNewsgroup->getListNewsgroup($start,$size,array('keyword'=>$keyword));
		$paging = trim($objUtil->paging($page, $size, $totalRecord));
		$ajaxData .= ' <div class="box-header blue-background">
                      <div class="title">Danh sách các Nhóm  tin</div>
                      <div class="actions">
                        <a class="btn box-remove btn-xs btn-link" href="#"><i class="icon-remove"></i>
                        </a>
		
                        <a class="btn box-collapse btn-xs btn-link" href="#"><i></i>
                        </a>
                      </div>
                    </div>
                    <div class="box-content box-no-padding">
                      <div class="responsive-table">';
		$ajaxData .= '<table class="table" style="margin-bottom:0;">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">STT</th>';
				$ajaxData .= '<th width="200">Tên nhóm</th>';
				$ajaxData .= '<th width="250">Mô tả</th>';
				$ajaxData .= '<th width="250">Ưu tiên</th>';
				$ajaxData .= '<th width="50" >#</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		$arrGroup = array();
		foreach($listNewsgroup as $ngr){
			$i++;
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$ngr['group_id'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td>'.$ngr['group_name'].'</td>';
			$ajaxData .= '<td>'.$objUtil->tooltipString($ngr['description'],50).'</td>';
			$ajaxData .= '<td>'.$ngr['group_order'].'</td>';
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
			$ajaxData .= '<a class="btn btn-xs" href="'.WEB_PATH.'/administrator/newsgroup/update/?id='.$ngr['group_id'].'"><i class="icon-edit"></i></a> <a class="btn btn-danger btn-xs" href="#" onclick="deleteNewsgroup('.$ngr['group_id'].')"><i class="icon-remove"></i></a> ';
			$ajaxData .= '</td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		$ajaxData .= '</div>
                      ';
		echo $objUtil->renderData($ajaxData,$paging);die();
	}
}
