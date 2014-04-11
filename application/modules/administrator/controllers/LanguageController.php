<?php
class Administrator_LanguageController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function importAction(){
		$fileName 		= @$this->_request->getParam('file');
		$objLanguage 	= new HT_Model_administrator_models_language();
		$objLanguage->importOneFile($fileName);
		$objLanguage->buldLangFile();
		echo 'done';die();	
	}
	
	public function updateAction(){
		$do = @$this->_request->getParam('do');
		$objLanguage 	= new HT_Model_administrator_models_language();
		if($do == 'submit'){
			$data = $_POST['data'];
			$arrLang = explode("\n", $data);
			$objLanguage->addData($arrLang);
			$objLanguage->buldLangFile();
			$this->_redirect(WEB_PATH.'/administrator/language');
		}
	}
	
	public function indexAction(){
		$objUtil 		= new HT_Model_administrator_models_utility();
		
		$do = @$this->_request->getParam('do');
		$id = (int)$this->_request->getParam('id');
		if($do == 'save'){
			$this->saveLanguage();
		}elseif($do == 'list'){
			$this->getListLanguage();
		}else{
			$keyword 				= $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/language/index.js');
	}
	
	public function saveLanguage(){
		$ids 			= $_POST['lang_id'];
		$values 		= $_POST['lang_value'];
		$objLanguage 	= new HT_Model_administrator_models_language();
		$objLanguage->saveLangs($ids,$values);
		$objLanguage->buldLangFile();
		echo 1; die();
	}

	function getListLanguage(){
	    
        $objUtil 		= new HT_Model_administrator_models_utility();
		$objLanguage 	= new HT_Model_administrator_models_language();
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
		
		$totalRecord 	= $objLanguage->getListLanguage_nb($filter);
		$listLanguage 	= $objLanguage->getListLanguage($start,$size,$filter);
		$paging 		= trim($objUtil->paging($page, $size, $totalRecord));

		$ajaxData = '<table cellspacing="0" class="table">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">No</th>';
				$ajaxData .= '<th width="200">Language key</th>';
				$ajaxData .= '<th width="700">Language Value</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		$arrGroup = array();
		foreach($listLanguage as $item){
			$i++;
			
			$ajaxData .= '<input type="hidden" name="lang_id[]" value="'.$item['lang_id'].'" />';
			
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$item['lang_id'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td>'.$item['lang_key'].'</td>';
			$ajaxData .= '<td><input type="text" style="width: 100%" name="lang_value[]" value="'.$item['lang_value'].'" /></td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		$title="Language management";
		$ajaxData = $objUtil->renderData($title,$ajaxData,$paging);
		
		$ajaxData .= "<div class='form-actions form-actions-padding-sm'>";
			$ajaxData .= "<div class='row'>";
				$ajaxData .= "<div class='col-md-9 col-md-offset-3'>";
					$ajaxData .= '<a onclick="saveLang();" class="btn btn-primary" >';
					$ajaxData .= "<i class='icon-save'></i>";
					$ajaxData .= ' Save';
					$ajaxData .= '</a>';
				$ajaxData .= '</div>';
			$ajaxData .= '</div>';
		$ajaxData .= '</div>';
		
		echo $ajaxData; die();
		
	}
}
