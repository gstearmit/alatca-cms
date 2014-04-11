<?php

class Administrator_NewsController extends Zend_Controller_Action {
	public $deniedIds = '';
    public function init() {
        
    }

    public function indexAction() {
        $objNews = new HT_Model_administrator_models_news();
        $do = @$this->_request->getParam('do');
        $id = (int) $this->_request->getParam('id');
        if ($do == 'delete' && $id > 0) {
            $this->deleteNews($id);
        } elseif ($do == 'list') {
            $this->getListNews();
        } else {
            $keyword = $this->_request->getParam('keyword');
            $this->view->keyword = $keyword;
        }
        $this->view->inlineScript()->appendFile(WEB_PATH . '/application/modules/administrator/views/scripts/news/index.js');
    }

    public function updateAction() {
    	$objUtil   = new HT_Model_administrator_models_utility();
        $objNews = new HT_Model_administrator_models_news();
        $do = @$this->_request->getParam('do');
        $id = (int) $this->_request->getParam('id');
        $ustatus = (int) $this->_request->getParam('ustatus');
        $category_ids = $this->_request->getParam('category_ids');
        $delete_image = @$this->_request->getParam('delete_image');
        
        if ($do == 'submit') {
        	$image = $objUtil->uploadFile('image',NEWS_IMAGE_PATH,MAX_IMAGE_FILE_SIZE,IMAGE_TYPE_ALLOW);

        	
            $data = array();
            if(!in_array($image,array(1,2,3,4))){
            	$data['image'] = $image;
            }
            $data['image_description'] = $this->_request->getParam('image_description');
            $data['title_vn'] 		= $this->_request->getParam('title_vn');
            $data['desc_vn'] 		= $this->_request->getParam('desc_vn');
            $data['status'] 		= $this->_request->getParam('status');
            $data['content_vn'] 	= $this->_request->getParam('content_vn');
            $data['created'] 		= date('Y-m-d H:i:s');
            if($delete_image) $data['image'] = null;          
            
            if ($id > 0) {
                $return = $objNews->updateData($data,$id);
            } else {
                $return = $objNews->addData($data);
                if($return >0){
                	$id = $return;
                	$objNews->updateCategories($category_ids,$id);
                }
            }
            
            if($return >0){
            	$this->_redirect(WEB_PATH.'/administrator/news/update?ustatus=1&id='.$id);
            }else{
            	$this->_redirect(WEB_PATH.'/administrator/news/update?ustatus='.$return.'&id='.$id);
            }
        } elseif ($id > 0) {
            $this->view->news = $objNews->getNews($id);
        }
        
        $groupList		   = $objNews->getGroupList();
        
        $this->view->id 		= $id;
        $this->view->ustatus 	= $ustatus;
        $this->view->groupList 	= $groupList;
        $this->view->inlineScript()->appendFile(WEB_PATH . '/application/modules/administrator/views/scripts/news/update.js');
    }

    function deleteNews($id) {
        $objNews = new HT_Model_administrator_models_news();
        echo $objNews->delete("id_news=".(int) $id);
        die();
    }

	function getListNews(){
		$objUtil 		= new HT_Model_administrator_models_utility();
		$objConvert		= new HT_Model_administrator_models_convert();
		$objNews 	= new HT_Model_administrator_models_news();
		$keyword 		= trim($this->_request->getParam('keyword'));
		$page 			= (int)$this->_request->getParam('page');
		$searchFor 		= $this->_request->getParam('search_for');
		
		$size 			= PAGING_SIZE;
		if (!is_numeric($page) || $page <= 0) {
			$page = 1;
		}
		$start = $page * $size - $size;
		
		$filter = array('denied_ids'=>$this->deniedIds);
		if($keyword) $filter['keyword'] = $keyword;
		if($searchFor == 'not_connected' || $searchFor == 'connected') $filter['search_for'] = $searchFor;
		
		
		$totalRecord = $objNews->getListNews_nb($filter);
		$listNews = $objNews->getListNews($start,$size,$filter);
		$paging = trim($objUtil->paging($page, $size, $totalRecord));
	
		$ajaxData = null;
		if($paging){
			$ajaxData .= '<div class="paging_box">';
			$ajaxData .= $paging;
			$ajaxData .= '<div class="cb"></div></div>';
		}
		$ajaxData .= ' <div class="col-sm-12">
                  <div class="box bordered-box blue-border" style="margin-bottom:0;">
                    <div class="box-header blue-background">
                      <div class="title">Danh sách tin bài</div>
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
		$ajaxData .= '<th width="400">Tiêu đề tiếng việt</th>';
		$ajaxData .= '<th width="400">Mô tả tiếng việt</th>';
		$ajaxData .= '<th width="100">Ngày tạo</th>';
		$ajaxData .= '<th width="50" >#</th>';
		$ajaxData .= '</tr>';
		$ajaxData .= '</thead>';
	
		$i=0;
		foreach($listNews as $news){
			$url = WEB_PATH."/chi-tiet/".$objConvert->utf8_to_url($news['newsTitle']).'-'.$news['newsId'].".html";
			$i++;
			$trClass = null;
			if($i%2 == 1) $trClass = ' class="altrow"';
			$ajaxData .= '<tr id="'.$news['newsId'].'" '.$trClass.'>';
			$ajaxData .= '<td align="center">'.$i.'</td>';
			$ajaxData .= '<td><a href="'.$url.'" target="_blank">'.$objUtil->tooltipString($news['newsTitle'],50).'</a></td>';
			$ajaxData .= '<td>'.$objUtil->tooltipString($news['description']).'</td>';
			$ajaxData .= '<td>'.$objUtil->normalDate($news['created']).'</td>';
			$ajaxData .= '<td style="white-space: nowrap" align="center">';
			$ajaxData .= '<a class="btn btn-xs" href="'.WEB_PATH.'/administrator/news/update/?id='.$news['newsId'].'"><i class="icon-refresh"></i></a> <a  class="btn btn-danger btn-xs" href="#" onclick="deleteNews('.$news['newsId'].')"><i class="icon-remove"></i></a>';
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
	
		$searchResult = "Có <b>".number_format($totalRecord, 0, '.', ',')."</b> Kết quả phù hợp với dữ liệu tìm kiếm";
		echo $ajaxData.':::'.$searchResult; die();
	}

}
