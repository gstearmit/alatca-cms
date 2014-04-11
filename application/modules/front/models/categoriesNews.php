<?php

class HT_Model_front_models_categoriesnews extends Zend_Db_Table{
    protected $db;
    public function __construct() {
        $this->db = Zend_Registry::get('dbMain');
        parent::init();
    }

    //loadNewsbyCateId
    public function loadnewsbyCate($cateId) {
        $result = $this->db->query("SELECT news.id_news,news.title_en,news.title_vn,news.des_en,news.creates,news.imagesWidth,user.userid,user.firstname,user.lastname,news.id_category,category.category_name FROM news JOIN USER ON news.userid=user.userid join category on news.id_category=category.id_category where news.id_category='" . $cateId . "' ORDER BY news.id_news DESC");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }

//loadNewsBytag
    public function loadnewsbyTag($tagId) {
        $result = $this->db->query("SELECT news.id_news,news.title_en,news.title_vn,news.des_en,creates,news.imagesWidth,user.userid,user.firstname,user.lastname,news.id_category,tagnews.tagId,tag.tagName,category.category_name FROM news JOIN USER ON news.userid=user.userid JOIN tagnews ON tagnews.newsId=news.id_news JOIN tag ON tagnews.tagId=tag.tagId join category on news.id_category=category.id_category WHERE tagnews.tagId='".$tagId."' ORDER BY news.id_news DESC");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }
    public function loadtagname($tagId) {
        $result = $this->db->query("SELECT * FROM tag  WHERE tagId='".$tagId."'");
        if ($result) {
            return $result->fetch();
        } else {
            return null;
        }
    }
//load news By tac gia.
    public function loadnewsbyuserPost($userId) {
        $result = $this->db->query("SELECT news.id_news,news.title_en,news.title_vn,news.des_en,news.creates,news.imagesWidth,user.userid,user.firstname,user.lastname,news.id_category,category.category_name FROM news JOIN USER ON news.userid=user.userid join category on news.id_category=category.id_category WHERE news.userid='".$userId."' ORDER BY news.id_news DESC");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }
    public function loadauthname($userId) {
        $result = $this->db->query("SELECT * FROM user WHERE userid='".$userId."'");
        if ($result) {
            return $result->fetch();
        } else {
            return null;
        }
    }
    //load news by date
    public function loadnewsbyDate($datePost) {
        $result = $this->db->query("SELECT news.id_news,news.title_en,news.title_vn,news.creates,news.imagesWidth,news.des_en,user.userid,user.firstname,user.lastname,news.id_category,category.category_name FROM news JOIN USER ON news.userid=user.userid join category on news.id_category=category.id_category WHERE news.creates='".$datePost."' ORDER BY news.id_news DESC");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }
    //load news by keysearch
    public function loadnewsbySearch($keyWord) {
        $result = $this->db->query("SELECT news.id_news,news.title_en,news.title_vn,news.creates,news.imagesWidth,news.des_en,user.userid,user.firstname,user.lastname,news.id_category FROM news JOIN USER ON news.userid=user.userid WHERE news.title_en LIKE '%".$keyWord."%' ORDER BY news.id_news DESC");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }
}
