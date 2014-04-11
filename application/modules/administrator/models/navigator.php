<?php

class HT_Model_administrator_models_navigator extends Zend_Db_Table {
	
	public $totalRow		= 0;
	public $limitStartRow	= 0;
	public $limitLengthRow	= 10;
	
	public $numCol 		= 1;
	public $numRow		= 10;
	
	public $currentPage	= 1;
	public $totalPage	= 1;
	
	public $pageNear	= 3;
	
	public $navigator	= array();
	
	
	/**
	 * This function will is first run
	 *
	 */
	function __construct($numCol, $numRow, $pageNear) {
		
		$this->buildCoreData($numCol, $numRow, $pageNear);
	}
	
	/**
	 * This function to destruct object
	 *
	 */
	function __destruct() {
		$this->totalRow 	= null;
		$this->numCol 		= null;
		$this->numRow 		= null;
		unset($this->totalRow, $this->numCol, $this->numRow);
	}
	
	/**
	 * Build core data for class
	 *
	 * @param integer $numCol
	 * @param integer $numRow
	 * @param integer $pageNear
	 */
	public function buildCoreData($numCol = null, $numRow = null, $pageNear = null) {
		if (!is_null($numCol)) 		$this->numCol 		= (int) $numCol;
		if (!is_null($numRow)) 		$this->numRow		= (int) $numRow;
		if (!is_null($pageNear))	$this->pageNear	= (int) $pageNear;
	}
	
	/**
	 * This function use to build Navigator
	 *
	 * @param integer $totalRow
	 * @param integer $currentPage
	 * @return array Navigator
	 */
	public function buildNavigator($totalRow = null, $currentPage = null, $extra_link = '') {
		if (is_numeric($totalRow) && $totalRow >0 ){
		    $this->totalRow	= (int) $totalRow;
		}
		if (is_numeric($currentPage) && $currentPage > 1){
		    $this->currentPage	= (int) $currentPage;
		}else{
		    $this->currentPage 	= 1;
		} 
		if($this->numCol * $this->numRow >0){
		    $this->totalPage = ceil($this->totalRow / ($this->numCol * $this->numRow));
		}
		if ($this->currentPage > $this->totalPage)	$this->currentPage = $this->totalPage;
		
		if ($this->currentPage > 1) {
			// General link first of navigator
			$this->navigator[] = array(	'isFirst' 	  => true,
										'link'		  => $this->modifyURL(@$GLOBALS['URI'], 'page', '1') . '&' . $extra_link,
										'numberPage'  => 1);
										
			// General link previous of navigator
			$this->navigator[] = array( 'isPrevious'  => true,
										'link'		  => $this->modifyURL(@$GLOBALS['URI'], 'page', $this->currentPage - 1) . '&' . $extra_link,
										'numberPage'  => $this->currentPage - 1);
		}
		
		if ($this->totalPage > 1) {
			$startPage 	= $this->currentPage - $this->pageNear;
			$endPage	= $this->currentPage + $this->pageNear;
			if ($startPage < 1)	$startPage = 1;
			if ($endPage > $this->totalPage) $endPage = $this->totalPage;
			
			while (($startPage > 0) && ($startPage <= $endPage)) {
				$this->navigator[] = array(	'isNum'		 => true,
											'link' 		 => $this->modifyURL(@$GLOBALS['URI'], 'page', $startPage) . '&' . $extra_link,
											'numberPage' => $startPage,
											'currentPage'=> (($this->currentPage == $startPage) ? true : false));
				$startPage ++;
			}
		}
		
		if ($this->currentPage < $this->totalPage) {
			// General link next of navigator
			$this->navigator[] = array(	'isNext' 	 => true,
										'link'		 => $this->modifyURL(@$GLOBALS['URI'], 'page', $this->currentPage + 1) . '&' . $extra_link,
										'numberPage' => $this->currentPage + 1);
			
			// General link last of navigator
			$this->navigator[] = array(	'isLast'	 => true,
										'link'		 => $this->modifyURL(@$GLOBALS['URI'], 'page', $this->totalPage) . '&' . $extra_link,
										'numberPage' => $this->totalPage);
		}
		
		// General Limit number for query
		$this->limitLengthRow 	= $this->numCol * $this->numRow;
		$this->limitStartRow	= $this->limitLengthRow * ($this->currentPage - 1);
		
		return $this->navigator;
	}	// End build Navigator
	
	public function renderNavigatorHTML($naviData = array()){
		$html = "";
		
		$hasFirst = 0;
		$hasLast  = 0;
		
		$navigator 	= @$naviData['data']; 
		$url 		= @$naviData['url']; 
		$first 		= @$naviData['first']; 
		$last 		= @$naviData['last']; 
		$next 		= @$naviData['next']; 
		$back 		= @$naviData['back']; 
		
	    if($first == ""){ $first = "<img src='" . JURI::base() . "templates/sun_admin_v1/images/paging/navig_first.gif' />"; }
		if($last == ""){ $last = "<img src='" . JURI::base() . "templates/sun_admin_v1/images/paging/navig_last.gif' />"; }
		if($next == ""){ $next = "<img src='" . JURI::base() . "templates/sun_admin_v1/images/paging/navig_next.gif' />"; }
		if($back == ""){ $back = "<img src='" . JURI::base() . "templates/sun_admin_v1/images/paging/navig_back.gif' />"; }
		
		if (!empty($navigator)) {
			foreach($navigator as $value) {
				$link = @$value['link'];
				$link	= str_replace("?","&",$link);
				$link = $url.$link;				
				if (@$value['isFirst']){
				    $hasFirst = 1;
				    $html.= '<a class="cp"  href="' .$link . '">'.$first.'</a> ';
				} 
				if (@$value['isPrevious']){
				     $html.= '<a class="cp" href="' .$link . '">'.$back.'</a>';
				     $html.= " <span>...</span>";
				}
				if (@$value['isNext']){
				    $html.= " <span>...</span>";
				    $html.= '<a class="cp" href="' .$link . '">'.$next.'</a>';
				} 
				if (@$value['isLast']){
				     $hasLast = 1;
				     $html.= ' <a class="cp" href="' .$link . '">'.$last.'</a> ';
				} 
				if (@$value['isNum']) {
					if (@$value['currentPage']) {
						$html.= '<span class="active"><a>'.@$value['numberPage'].'</a></span>' ;
					}
					else {
						$html.= '<a  class="cp nomal_pg" href="' .$link . '">' . @$value['numberPage'] . '</a>';
					}
				}
			}
		    if($hasFirst == 0){
    		    $html= "<img src='" . JURI::base() . "templates/sun_admin_v1/images/paging/navig_border_left.gif' align='left' />".$html;
    		}
    		if($hasLast == 0){
    		    $html.= "<img src='" . JURI::base() . "templates/sun_admin_v1/images/paging/navig_border_right.gif' />";
    		}
		}
		return $html;
	}
	
    public function renderAjaxNavigatorHTML($naviData = array(),$currentForm=null){	
		$hasFirst = 0;
		$hasLast  = 0;
		$navigator 	= @$naviData['data']; 
		$url 		= @$naviData['url']; 
		$first 		= @$naviData['first']; 
		$last 		= @$naviData['last']; 
		$next 		= @$naviData['next']; 
		$back 		= @$naviData['back']; 
		
		$orderBy 	= @$naviData['orderBy'];
		$direction 	= @$naviData['direction'];
		
		if($first == ""){ $first = '<span class="nomal">Đầu < </span>'; }
		if($last == ""){ $last = '<span class="nomal"> > Cuối</span>'; }
		if($next == ""){ $next = '<span class="nomal"> > Sau</span>'; }
		if($back == ""){ $back = '<span class="nomal">Trước < </span>'; }
		$html = '<div class="dataTables_paginate paging_bootstrap text-right">';
		if (!empty($navigator)) {
			
			$html .= '<ul class="pagination pagination-sm">';
			foreach($navigator as $value) {
				$link = @$value['link'];
				$link	= str_replace("?","&",$link);
				$link = $url.$link;
				if (@$value['isFirst']){
				    $hasFirst = 1;
				    $html.= '<li><a class="cp" onclick="buildNavigator(\''.@$value['numberPage'] .'\',\''.$currentForm.'\',\''.$orderBy.'\',\''.$direction.'\'); return false;">'.$first.'</a></li>';
				}
				if (@$value['isPrevious']){
				    $html.= '<li><a class="cp" onclick="buildNavigator(\''.@$value['numberPage'] .'\',\''.$currentForm.'\',\''.$orderBy.'\',\''.$direction.'\'); return false;">'.$back.'</a></li>';
				}
				if (@$value['isNext']){
				    $html.= '<li><a class="cp" onclick="buildNavigator(\''.@$value['numberPage'] .'\',\''.$currentForm.'\',\''.$orderBy.'\',\''.$direction.'\'); return false;">'.$next.'</a></li>';
				}
				if (@$value['isLast']){
				     $hasLast = 1;
				     $html.= '<li><a class="cp" onclick="buildNavigator(\''.@$value['numberPage'] .'\',\''.$currentForm.'\',\''.$orderBy.'\',\''.$direction.'\'); return false;">'.$last.'</a></li>';   
				}
				if (@$value['isNum']) {
					if (@$value['currentPage']) {
						$html.= '<li><a class="active">'.@$value['numberPage'].'</a></li>' ;
					}else{
						$html.= '<li><a class="cp nomal_pg" onclick="buildNavigator(\''.@$value['numberPage'] .'\',\''.$currentForm.'\',\''.$orderBy.'\',\''.$direction.'\'); return false;">' . @$value['numberPage'] . '</a></li>';
					}
				}
			}
			$html .= '</ul>';
		}
		$html .= '</div>';
		return $html;
	}
	
    public function renderAjaxNavigatorHTMLUseAjaxLoadPage($naviData = array(),$div_id,$nav_params = ''){
		$html = "";
		$navigator 	= @$naviData['data']; 
		$url 		= @$naviData['url']; 
		$first 		= @$naviData['first']; 
		$last 		= @$naviData['last']; 
		$next 		= @$naviData['next']; 
		$back 		= @$naviData['back']; 
		
		$orderBy 	= @$naviData['orderBy'];
		$direction 	= @$naviData['direction'];
		
		if($first == ""){ $first = "&lt;&lt;"; }
		if($last == ""){ $last = "&gt;&gt;"; }
		if($next == ""){ $next = "&gt;"; }
		if($back == ""){ $back = "&lt;"; }		
		$nav_params = $nav_params?$nav_params:$nav_params . "&";
		if($orderBy){
		    $nav_params .= "orderBy=" . $orderBy;
		}
		if($direction){
		    $nav_params .= "&direction=" . $direction;
		}
		
		if (!empty($navigator)) {
			foreach($navigator as $value) {
			    $param    =    $nav_params. "&page=" . @$value['numberPage'];				
				if (@$value['isFirst']) $html.= '<a class="pg_first bg_link_text" href="#;" onclick="AjaxLoadPage(\''.$div_id .'\',\''.$param.'\',\'get\',\''.$url.'\'); return false;">'.$first.'</a> ';
				if (@$value['isPrevious']) $html.= '<a class="pg_back bg_link_text" href="#;" onclick="AjaxLoadPage(\''.$div_id .'\',\''.$param.'\',\'get\',\''.$url.'\'); return false;">'.$back.'</a>';
				if (@$value['isNext']) $html.= '<a class="pg_next bg_link_text" href="#;" onclick="AjaxLoadPage(\''.$div_id .'\',\''.$param.'\',\'get\',\''.$url.'\'); return false;">'.$next.'</a>';
				if (@$value['isLast']) $html.= ' <a class="pg_last bg_link_text" href="#;" onclick="AjaxLoadPage(\''.$div_id .'\',\''.$param.'\',\'get\',\''.$url.'\'); return false;">'.$last.'</a> ';
				if (@$value['isNum']) {
					if (@$value['currentPage']) {
						$html.= '<span class="pg_current_page">'.@$value['numberPage'].'</span>' ;
					}
					else {
						$html.= '<a  class="pg_other_page bg_link_text" href="#;" onclick="AjaxLoadPage(\''.$div_id .'\',\''.$param.'\',\'get\',\''.$url.'\'); return false;">' . @$value['numberPage'] . '</a>';
					}
				}
			}
		}
		return $html;
	}
	
	/**
	 * This function to modify URL by field name & value
	 *
	 * @param string $url
	 * @param string $field
	 * @param string $value
	 * @return string
	 */
	function modifyURL($url, $field, $value, $postion = null) {
	
		if (empty($url))	$url = @$GLOBALS['URI'];
		if (empty($field)) 	return $url;
	
		$pattern = "/$field=[^\?\&]+/i";
	
		if (empty($value)) $url = preg_replace($pattern, '', $url);
		elseif(!preg_match($pattern, $url)) {
			$url .= (strpos($url, '?') === false) ? '?' : '&amp;';
			$url .= $field . '=' . urlencode($value);
		}
		else {
			$url = preg_replace($pattern, "{$field}=".urlencode($value), $url);
		}
	
		$url = preg_replace('/\&+/i', '&', $url);
		$url = preg_replace('/(.*)\&$/i', '$1', $url);
		$url = str_replace('?&', '?', $url);
	
		return $url;
	
	}	// end function mofifyURI
}
