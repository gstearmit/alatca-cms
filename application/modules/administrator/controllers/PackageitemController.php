<?php
class Administrator_PackageitemController extends Zend_Controller_Action
{
	public function init() {
		
	}
	
	public function indexAction(){
		$do = @$this->_request->getParam('do');
		$id = (int)$this->_request->getParam('id');
		if($do == 'delete' && $id >0){
			$this->deletePackageitem($id);
		}elseif($do == 'list'){
			$this->getListPackageitem();
		}else{
			$keyword 				= $this->_request->getParam('keyword');
			$this->view->keyword 	= $keyword;
		}
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/packageitem/index.js');
	}
	
	public function updateAction(){
		$objPackageitem 	= new HT_Model_administrator_models_packageitem();
		$objUtil 			= new HT_Model_administrator_models_utility();
		$do 		 		= @$this->_request->getParam('do');
		$id 				= (int)$this->_request->getParam('id');
		$status 			= (int)$this->_request->getParam('status');
		$item_status				= $this->_request->getParam('item_status');
		//echo $item_status;die();
		$packageId			= null;
		if($do == 'submit'){
			$data = array();
			$data['item_name'] 		= ucfirst(trim($this->_request->getParam('item_name')));
			$data['item_status'] 		= $this->_request->getParam('item_status');
			$data['description'] 	= $this->_request->getParam('description');
			$data['item_order'] 	= $this->_request->getParam('item_order');
			$data['package_id'] 	= $this->_request->getParam('package_id');
			if($id >0){
				$status = $objPackageitem->updateData($data,(int)$id);
			}else{
				$status = $objPackageitem->addData($data);
			}

			if($status > 0){
				$this->_redirect(WEB_PATH.'/administrator/packageitem');
			}else{
				$redirectLink = WEB_PATH."/administrator/packageitem/update?status=$status";
				if($id >0) $redirectLink .= "&id=$id";
				$this->_redirect($redirectLink);
			}
		}elseif($id >0){
			$packageitem		= $objPackageitem->getPackageitem($id);
			$packageId			= $packageitem['package_id'];
			$this->view->pki 	= $packageitem;
		}
		
		$package 				= $objUtil->GetCombobox('package_id', 'package_id', 'package_name', 'package',array('defaultValue'=>$packageId,'isBlankVal'=>'Please select a package','cssClass'=>'form-control'));
		
		$this->view->id 		= $id;
		$this->view->status 	= $status;
		$this->view->package 	= $package;
		$this->view->inlineScript()->appendFile(WEB_PATH.'/application/modules/administrator/views/scripts/packageitem/update.js');
	}

	function deletePackageitem($id){
		$objPackageitem = new HT_Model_administrator_models_packageitem();
		echo $objPackageitem->delete("item_id=".(int)$id);die();
	}

	function getListPackageitem(){
	    
        $objUtil 		= new HT_Model_administrator_models_utility();
		$objPackageitem 		= new HT_Model_administrator_models_packageitem();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$size 			= PAGING_SIZE;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		$totalRecord = $objPackageitem->getListPackageitem_nb(array('keyword'=>$keyword));
		$listPackageitem = $objPackageitem->getListPackageitem($start,$size,array('keyword'=>$keyword));
		$paging = trim($objUtil->paging($page, $size, $totalRecord));

		$ajaxData = '<table cellspacing="0" class="table">';
		$ajaxData .= '<thead>';
			$ajaxData .= '<tr>';
				$ajaxData .= '<th width="15">No</th>';
				$ajaxData .= '<th width="100">Package name</th>';
				$ajaxData .= '<th width="400">Features</th>';
				$ajaxData .= '<th width="150">Status</th>';
				$ajaxData .= '<th width="80">Order</th>';
				$ajaxData .= '<th style="white-space: nowrap;padding-right: 5px;" align="center">#</th>';
			$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
		
		$i=0;
		$arrGroup 	= array();
		$arrName	= array();
		foreach($listPackageitem as $pki){
			$i++;
			$trClass = null;
			$packageName = $pki['package_name'];
			if((int)$pki['item_status'] == 3){
				$itemStatusDisplay = 'Plus';
			}elseif((int)$pki['item_status'] == 2){
				$itemStatusDisplay = 'Yes';
			}else{
				$itemStatusDisplay = 'No';
			}
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$pki['item_id'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td>';
				if(!in_array($packageName,$arrName)){
					$ajaxData .= $packageName;
					$arrName[] = $packageName;
				} 
			$ajaxData .= '</td>';
			$ajaxData .= '<td>'.$pki['item_name'].'</td>';
			$ajaxData .= '<td>'.$itemStatusDisplay.'</td>';
			$ajaxData .= '<td>'.$pki['item_order'].'</td>';
		
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
				
			$ajaxData .='
			<div class="text-center">
			<a href="'.WEB_PATH.'/administrator/packageitem/update/?id='.$pki['item_id'].'" class="btn btn-xs" title="Edit">
			<i class="icon-pencil"></i>
			</a>
			<a href="#" onclick="deletePackageitem('.$pki['item_id'].')" class="btn btn-danger btn-xs"  title="Delete">
			<i class=" icon-trash "></i>
			</a>
			</div>
			';
			
			$ajaxData .= '</td>';
			$ajaxData .= '</tr>';
		}
		$ajaxData .= '</tbody>';
		$ajaxData .= '</table>';
		$title="Management features of the package";
		echo $objUtil->renderData($title,$ajaxData,$paging);die();
	}
}
