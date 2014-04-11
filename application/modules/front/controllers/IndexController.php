<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        $this->_sess = new Zend_Session_Namespace();
    }

    public function indexAction() {
        $objHome = new HT_Model_front_models_homemodel();
        //load menu.
        $listCate = $objHome->loadCate();

        $data = array();
        $index=0;
        foreach ($listCate as $keyId => $dataList) {
            $cateId = $dataList["id_category"];
            $data[$index] = $objHome->loadHomeNewsByCate($cateId);
             $index++;
        }
        $this->view->listcate = $listCate; //load detal tin tuc
        $this->view->ListHomeNews = $data; //load tag news
    }

    public function detailActon() {
        $newsId = $this->_request->getQuery("newsId");
        $this->_sess->newsId = $newsId;
        $objHome = new HT_Model_front_models_homemodel();
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

    public function postCmt() {
        if (isset($this->_sess->userId)) {
            if ($this->_request->isPost()) {
                $objHome = new HT_Model_front_models_homemodel();
                $newsId = $this->_sess->newsId;
                $userId = $this->_sess->userId;
                $cmtCt = $this->_request->getPost("cmtCt");
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $addCmt = $objHome->postCmt($userId, $cmtCt, $newsId, date('Y-m-d H:i:s'));
                if ($addCmt) {
                    echo "post comment thanh cong.";
                } else {
                    echo "Post coment that bai.";
                }
            }
        }
    }

}

?>