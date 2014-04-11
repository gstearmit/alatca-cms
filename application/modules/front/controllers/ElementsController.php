<?php

class ElementsController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function menutopAction() {
        $objHome = new HT_Model_front_models_homemodel();
        $listCate = $objHome->loadCate();
        $this->view->cate = $listCate; //danh sach cate thuoc nhom
        
    }

    public function newstrendingAction() {
        $objHome = new HT_Model_front_models_homemodel();
        $listSlider = $objHome->loadSliderNewsTop();
        $this->view->sliderTop = $listSlider;
    }

    public function loadslidertopAction() {
        $objHome = new HT_Model_front_models_homemodel();
        $listSlider = $objHome->loadSliderNewsTop();
        $this->view->sliderTop = $listSlider; //danh sach slider top
    }

    public function loadgroupAction() {
        $objHome = new HT_Model_front_models_homemodel();
        $listCate = $objHome->loadCate();
        $this->view->cate = $listCate; //danh sach cate thuoc nhom
        
    }

    public function tabpostAction() {
        $objHome = new HT_Model_front_models_homemodel();
        $recentPost = $objHome->recentPost();
        $newsView = $objHome->newsView();
        $recentComment = $objHome->recentComment();

        //tagHome
        $recenttag = $objHome->recenttag();
        $this->view->newsView = $newsView; //5 ba viet dc nheieu nguoi em nhat
        $this->view->recentPost = $recentPost;
        $this->view->recentCmt = $recentComment; //binh luan moi nhat
        $this->view->tag = $recenttag; //tag home
    }

    public function randpostAction() {
        $objHome = new HT_Model_front_models_homemodel();
        $listNewsRand = $objHome->randomPost();
        $this->view->randPost = $listNewsRand; //danh sach rand post news
    }

    public function newspopularAction() {
        $objHome = new HT_Model_front_models_homemodel();
        $listNewsPopu = $objHome->newsPopular();
        $this->view->popuPost = $listNewsPopu; //danh sach rand post news
    }

    public function slidershowAction() {
        $objHome = new HT_Model_front_models_homemodel();
        $listSliderShow = $objHome->sliderShow();
        $this->view->sliderShow = $listSliderShow;
    }

    public function followAction() {
        
    }

    public function footerAction() {
        
    }

    public function likefaceAction() {
        
    }

    public function pollAction() {
        
    }

    public function soundAction() {
        
    }

    public function subfooterAction() {
        
    }

    public function sponsorAction() {
        
    }

}

?>