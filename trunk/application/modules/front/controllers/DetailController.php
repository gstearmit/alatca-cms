<?php

class DetailController extends Zend_Controller_Action {

    public function init() {
        $this->_sess = new Zend_Session_Namespace();
    }

    public function indexAction() {
        $newsId = $this->_request->getParam("newsId");

        $this->_sess->newsId = $newsId;
        $objHome = new HT_Model_front_models_detail();
        //detail news
        $detailNews = $objHome->detailNew($newsId);
        //tag bai viet
        $tagNew = $objHome->tagNew($newsId);
        //bai viet lien quan
        $cateIdPostll = $objHome->cateIdPostll($newsId);
        $cateId = $cateIdPostll["id_category"];
        $listPostll = $objHome->listPostll($cateId);
        //show cmt
        //$loadCmt = $objHome->loadCmt($newsId);
        $this->view->headTitle($detailNews["title_en"]);
        $this->view->headMeta()->setName('og:title', $detailNews["title_en"]);
        $this->view->headMeta()->setName('description', $detailNews["des_en"]);
        //
        //send view
        $this->view->detailPost = $detailNews; //load detal tin tuc
        $this->view->tagNews = $tagNew; //load tag news
        $this->view->postll = $listPostll; //load danh sach bai viet lien quan
        // $this->view->listCmt = $loadCmt; //load cmt cua bai viet
    }

    public function detailActon() {
        $newsId = $this->_request->getQuery("newsId");
        $this->_sess->newsId = $newsId;
        $objHome = new HT_Model_homemodel();
        //detail news
        $detailNews = $objHome->detailNew($newsId);
        //tag bai viet
        $tagNew = $objHome->tagNew($newsId);
        //bai viet lien quan
        $cateIdPostll = $objHome->cateIdPostll($newsId);
        $cateId = $cateIdPostll["id_category"];
        $listPostll = $objHome->listPostll($cateId);
        //show cmt
        $loadCmt = $objHome->loadCmt($newsId);

        //
        //send view
        $this->view->detailPost = $detailNews; //load detal tin tuc
        $this->view->tagNews = $tagNew; //load tag news
        $this->view->postll = $listPostll; //load danh sach bai viet lien quan
        $this->view->listCmt = $loadCmt; //load cmt cua bai viet
    }

}
