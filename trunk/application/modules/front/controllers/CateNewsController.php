<?php

class CatenewsController extends Zend_Controller_Action {

    public $keyw;

    public function init() {
        $this->_sess = new Zend_Session_Namespace();
    }

    public function indexAction() {
        $base = $this->_request->getBaseUrl();
        $this->view->base = $base;
    }

//tat ca tin tuc theo danh muc
    public function catenewsallAction() {
        $cateId = $this->_request->getParam("cateId");

        $muser = new HT_Model_front_models_categoriesnews();
        $cate = $muser->loadnewsbyCate($cateId);
        $cateName = '';
        foreach ($cate as $keyId => $dataCate) {
            if ($keyId == 0) {
                $cateName = $dataCate["category_name"];
            }
        }
        $this->view->base = $this->_request->getBaseUrl();
        $paginator = Zend_Paginator::factory($muser->loadnewsbyCate($cateId));
        $paginator->setItemCountPerPage(2);
        $paginator->setPageRange(10);
        $currentPage = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $this->view->listNewsCate = $paginator;
        $this->view->cateName = $cateName;
    }

//tat ca tin tuc theo tag
    public function newsallbytagAction() {
        $tagId = $this->_request->getParam("tagId");
        
        $muser = new HT_Model_front_models_categoriesnews;
        $paginator = Zend_Paginator::factory($muser->loadnewsbyTag($tagId));
        $dataTag=$muser->loadtagname($tagId);
        $tagName=$dataTag["tagName"];
      
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);
        $currentPage = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $this->view->listNewstag = $paginator;
        $this->view->keyName =$tagName;
    }

    //tat ca tin tuc cua cung nguoi viet
    public function newsallbyauthAction() {
        $authId = $this->_request->getParam("authId");
        $muser = new HT_Model_front_models_categoriesnews;
        $dataTag=$muser->loadauthname($authId);
        $authName=$dataTag["firstname"]." ".$dataTag["lastname"];
        $paginator = Zend_Paginator::factory($muser->loadnewsbyuserPost($authId));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);
        $currentPage = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $this->view->listNewsAuth = $paginator;
        $this->view->keyName =$authName;
    }

    //tat ca bai viet theo cung ngay dang.
    public function newsallbydateAction() {
        $datePost = $this->_request->getParam("day");
        $this->view->keyName =$datePost;
      $datePost= date('Y-m-d',strtotime($datePost));
        $muser = new HT_Model_front_models_categoriesnews;
        $paginator = Zend_Paginator::factory($muser->loadnewsbyDate($datePost));
     
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(10);
        $currentPage = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $this->view->listNewsdate = $paginator;
        
    }

    //tat ca bai viet theo tu khoa tim kiem
    public function newallsearchAction() {
        if ($this->_request->isPost()) {
            $keyWord = $this->_request->getPost("keyWord");
            if ($keyWord != "") {
                echo "lan 2";
                $this->keyw = $keyWord;
            }
        }
        if ($this->keyw == '') {
            echo"Rong";
        }
        echo $this->keyw;
        $muser = new HT_Model_front_models_categoriesnews;
        $paginator = Zend_Paginator::factory($muser->loadnewsbySearch($this->keyw));

        $paginator->setItemCountPerPage(3);
        $paginator->setPageRange(3);

        $currentPage = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($currentPage);

        $this->view->listNewskey = $paginator;
        $this->view->keyName = $this->keyw;
    }

}
