<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class HT_Model_front_models_homemodel extends Zend_Db_Table {

    protected $_db;

    public function __construct() {
        $this->_db = Zend_Registry::get('dbMain');
        parent::init();
    }

    //load group
    public function group() {
       /* $result = $this->_db->query("SELECT * FROM groupcate");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }*/
    }

    //load categories by group
    public function loadCate() {
        $sql = $this->_db->query("SELECT * FROM category WHERE active='true'");
        if ($sql) {
            return $sql->fetchAll();
        } else {
            return null;
        }
    }
  

    //hien thi 15 bài viet slider trang chu
    public function loadSliderNewsTop() {
        $result = $this->_db->query("SELECT news.id_news,news.title_en,news.title_vn,news.creates,news.images,user.userid,user.firstname,user.lastname,news.id_category,category.category_name FROM news JOIN USER ON news.userid=user.userid join category on news.id_category=category.id_category WHERE STATUS='true' ORDER BY news.id_news DESC LIMIT 15");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }

//Hien thi phan loai theo group
    public function loadHomeNewsByCate($cate) {
        $result = $this->_db->query("SELECT news.id_news,news.title_en,news.title_vn,news.creates,news.images,news.imagesWidth,news.imagesVuong,user.userid,user.firstname,user.lastname,news.id_category,category.category_name FROM news JOIN USER ON news.userid=user.userid join category on news.id_category=category.id_category  WHERE news.id_category='" . $cate . "' ORDER BY news.id_news DESC  LIMIT 10");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }

    //ramdom post
    public function randomPost() {
        $result = $this->_db->query("SELECT news.id_news,news.title_en,news.title_vn,news.creates,news.imagesVuong,user.userid,user.firstname,user.lastname,news.id_category,category.category_name FROM news JOIN USER ON news.userid=user.userid join category on news.id_category=category.id_category ORDER BY RAND() DESC LIMIT 4");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }

    // tin tuc duoc nhieu nguoi xem nhat.
    public function newsPopular() {
        $result = $this->_db->query("SELECT news.id_news,news.title_en,news.title_vn,news.creates,news.imagesVuong,user.userid,user.firstname,user.lastname,news.view,category.category_name FROM news JOIN USER ON news.userid=user.userid join category on news.id_category=category.id_category ORDER BY news.view DESC LIMIT 4");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }

    //slider show
    public function sliderShow() {
        $result = $this->_db->query("SELECT * FROM slider");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }

    //5 bài dang gan nhat.
    public function recentPost() {
        $result = $this->_db->query("SELECT news.id_news,news.title_en,news.title_vn,news.creates,news.imagesVuong,user.userid,user.firstname,user.lastname,news.id_category,category.category_name FROM news JOIN USER ON news.userid=user.userid join category on news.id_category=category.id_category WHERE STATUS='true' ORDER BY news.id_news DESC LIMIT 5");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }

    //5 bai dc nhieu nguoi xem nhat.
    public function newsView() {
        $result = $this->_db->query("SELECT news.id_news,news.title_en,news.title_vn,news.creates,news.imagesVuong,user.userid,user.firstname,user.lastname,news.view,category.category_name FROM news JOIN USER ON news.userid=user.userid join category on news.id_category=category.id_category WHERE STATUS='true' ORDER BY news.view DESC LIMIT 5");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }

    //comment moi nhat
    public function recentComment() {
        $result = $this->_db->query("SELECT member.fullName,member.avata,comment.cmtId,comment.cmtcontent FROM member JOIN COMMENT ON member.memberId = comment.memberId ORDER BY comment.cmtId DESC  LIMIT 5 ");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }
//tag
    public function recenttag() {
        $result = $this->_db->query("SELECT * FROM tag ORDER BY tagId DESC");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }
//search keyword
    
}
