<?php
class Administrator_NewscategoryController extends Zend_Controller_Action
{
	public function init() {
	}
	
	public function indexAction(){
	    $order_field             =  $this->_request->getParam('order',false);
        $type                    =  $this->_request->getParam('type',false);
		   
		$do = @$this->_request->getParam('do');
		$id = (int)$this->_request->getParam('id');
		if($do == 'delete' && $id >0){
			$this->deleteNewscategory($id);
		}elseif($do == 'list'){
			$this->getListNewscategory();
		}else{
			$keyword = $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/newscategory/index.js');
	}
	
	public function updateAction(){
		$objNewscategory = new HT_Model_administrator_models_newscategory();
		$do 		 = @$this->_request->getParam('do');
		$id = (int)$this->_request->getParam('id');
		$status 	= (int)$this->_request->getParam('status');
		$groupId	= null;
		//echo $do; die();
		if($do == 'submit'){
			$active = (int)$this->_request->getParam('active');
			if($active != 1) $active = 0;
			$data = array();
			$data['category_name'] 		= $this->_request->getParam('category_name');
			$data['group_id'] 			= $this->_request->getParam('group_id');
			$data['active'] 			= $active;
			$data['stt'] 				= (int)$this->_request->getParam('stt');
			$data['description'] 		= $this->_request->getParam('description');
			if($id >0){
				$objNewscategory->update($data, 'id='.(int)$id);
			}else{
				$id = $objNewscategory->addData($data);
			}
			//$this->_redirect(WEB_PATH.'/administrator/newscategory/update?status=1&id='.$id);
			$this->_redirect(WEB_PATH.'/administrator/newscategory');
		}elseif($id >0){
			$newscategory			= $objNewscategory->getNewscategory($id);
			$groupId				= $newscategory['group_id'];
			$this->view->newscategory = $newscategory;
		}
		
		$objUtil 			= new HT_Model_administrator_models_utility();
		$newsGroup 			= $objUtil->GetCombobox('group_id','group_id','group_name','news_group',array('isBlankVal'=>'Vui lòng chọn nhóm tin','defaultValue'=>$groupId));
		
		$this->view->newsGroup  		= $newsGroup;
		
		$this->view->id = $id;
		$this->view->status 	 = $status;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/newscategory/update.js');
	}

	function deleteNewscategory($id){
		$objNewscategory = new HT_Model_administrator_models_newscategory();
		echo $objNewscategory->delete("id=".(int)$id);die();
	}

	function getListNewscategory(){
        $objUtil 		= new HT_Model_administrator_models_utility();
		$objNewscategory 	= new HT_Model_administrator_models_newscategory();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$size 			= PAGING_SIZE;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		$totalRecord = $objNewscategory->getListNewscategory_nb(array('keyword'=>$keyword));
		$listNewscategory = $objNewscategory->getListNewscategory($start,$size,array('keyword'=>$keyword));
		$paging = trim($objUtil->paging($page, $size, $totalRecord));

		
		$ajaxData = null;
		/*
		if($paging){
			$ajaxData .= '<div class="paging_box">';
			$ajaxData .= $paging;
			$ajaxData .= '<div class="cb"></div></div>';
		}
		*/
		$ajaxData .= ' <div class="col-sm-12">
                  <div class="box bordered-box blue-border" style="margin-bottom:0;">
                    <div class="box-header blue-background">
                      <div class="title">Danh sách các loại tin</div>
                      <div class="actions">
                        <a class="btn box-remove btn-xs btn-link" href="#"><i class="icon-remove"></i>
                        </a>
                        
                        <a class="btn box-collapse btn-xs btn-link" href="#"><i></i>
                        </a>
                      </div>
                    </div>
                    <div class="box-content box-no-padding">
                      <div class="responsive-table">
                        <div class="scrollable-area">  ';
		$ajaxData .= '<table class="table" style="margin-bottom:0;">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">STT</th>';
				$ajaxData .= '<th width="200">Nhóm tin</th>';
				$ajaxData .= '<th width="200">Tên loại tin</th>';
				$ajaxData .= '<th width="80">Kích hoạt</th>';
				$ajaxData .= '<th width="100">Sắp xếp</th>';
				$ajaxData .= '<th width="250">Mô tả</th>';
				$ajaxData .= '<th width="50">#</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		$arrGroup = array();
		foreach($listNewscategory as $cate){
			$i++;
			$groupName = $cate['group_name'];
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$cate['id'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td>';
				if(!in_array($groupName,$arrGroup)){
					$ajaxData .= '<b>'.$groupName.'</b>';
					$arrGroup[] = $groupName;
				} 
			$ajaxData .= '</td>';
			$ajaxData .= '<td><a href="'.WEB_PATH.'/administrator/newscategory/update/?id='.$cate['id'].'">'.$objUtil->tooltipString($cate['category_name'],200).'</a></td>';
			$ajaxData .= '<td>'.$cate['active'].'</td>';
			$ajaxData .= '<td>'.$cate['stt'].'</td>';
			$ajaxData .= '<td>'.$objUtil->tooltipString($cate['description']).'</td>';
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
			$ajaxData .= '<a  class="btn btn-xs" href="'.WEB_PATH.'/administrator/newscategory/update/?id='.$cate['id'].'"><i class="icon-edit"></i></a><a href="#" class="btn btn-danger btn-xs" onclick="deleteNewscategory('.$cate['id'].')"><i class="icon-remove"></i></a>';
			$ajaxData .= '</td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		$ajaxData .= '</div>
                      </div>
                    </div>
                  </div>
                </div> ';
		if($paging){
			$ajaxData .= '<div class="paging_box">';
			$ajaxData .= $paging;
			$ajaxData .= '<div class="cb"></div></div>';
		}
		echo $ajaxData; die();
	}
}
