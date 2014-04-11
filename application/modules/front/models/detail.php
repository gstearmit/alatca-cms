<?php

class HT_Model_front_models_detail extends Zend_Db_Table{

    protected $db;
    public function __construct() {
        $this->db = Zend_Registry::get('dbMain');
        parent::init();
    }
//Chi tiết bài viết theo Id,Ngày đăng,người đăng,số lượng cmt,thông tin tác giả,nội dung tiêu đề hình ảnh bài viets.
    public function detailNew($newsId) {
        $result = $this->db->query("SELECT news.id_news,news.title_vn,news.title_en,news.content_vn,news.content_en,news.des_en,news.images,news.creates,news.userid,user.firstname,user.lastname,user.avatar,user.info FROM news JOIN USER ON news.userid=user.userid WHERE news.id_news='" . $newsId . "'");
        if ($result) {
            return $result->fetch();
        } else {
            return null;
        }
    }
//hien thi cac tag liên quan của bài viết.
    public function tagNew($newsId) {
        $result = $this->db->query("SELECT tag.tagId,tag.tagName,news.id_news FROM tag JOIN tagnews ON tag.tagId=tagnews.tagId JOIN news ON tagnews.newsId=news.id_news WHERE news.id_news='".$newsId."'");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }
    //lay cate theo chi tiet bai viet de lay Id cate lam bai viet ll.
    public function cateIdPostll($newsId) {
        $result = $this->db->query("SELECT news.id_news,news.id_category FROM news WHERE news.id_news='".$newsId."'");
        if ($result) {
            return $result->fetch();
        } else {
            return null;
        }
    }
    //list bai viet co the ban quan tam.
    public function listPostll($cateId) {
        $result = $this->db->query("SELECT news.id_news,news.title_en,news.title_vn,news.creates,news.imagesWidth,user.userid,user.firstname,user.lastname,news.id_category,category.category_name FROM news JOIN USER ON news.userid=user.userid join category on news.id_category = category.id_category WHERE news.id_category='" . $cateId . "' ORDER BY RAND() DESC LIMIT 6");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }
    //insert cmt
    public function postCmt($memId,$cmtCt,$newsId,$dateCmt) {
        $result = $this->db->query("INSERT INTO COMMENT(cmtcontent,news_id,memberId,dateCmt) VALUE('".$cmtCt."','".$newsId."','".$memId."','".$dateCmt."')");
        if ($result>0) {
            return true;
        } else {
            return false;
        }
    }
    //loadAllCmt
    
    public function loadCmt($newsId) {
        $result = $this->db->query("SELECT comment.cmtId,comment.cmtcontent,comment.dateCmt,member.fullName,member.avata WHERE comment.news_id='".$newsId."'");
        if ($result) {
            return $result->fetchAll();
        } else {
            return null;
        }
    }
}
