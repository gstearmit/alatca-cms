<?php

class Model_Member extends Zend_Db_Table{
    public function __construct() {
        $this->_db = Zend_Registry::get('dbMain');
        parent::init();
    }
    //login
    public function login($userName,$password){
        $result=$this->db->query("SELECT * FROM member WHERE userName='".$userName."' AND PASSWORD='".$password."'");
        if($result){
            return $result->fetch();
        }else{
            return null;
        }
    }
    //load profile edit.
    public function loadEdit($userId){
        $result=$this->db->query("SELECT * FROM member WHERE memberId='".$userId."'");
        if($result){
            return $result->fetch();
        }else{
            return null;
        }
    }
    //update profile
     public function updateProfile($urlImages,$fullname,$dateofbirth,$userId){
        $result=$this->db->query("UPDATE member SET avata='".$urlImages."',fullName='".$fullname."',dateOfBirth='".$dateofbirth."' WHERE memberId='".$userId."'");
        if($result>0){
            return true;
        }else{
            return false;
        }
    }
    //reset password
    public function resetPass($email,$pass){
        $result=$this->db->query("UPDATE member SET password='".$pass."' WHERE email='".$email."'");
        if($result>0){
            return true;
        }else{
            return false;
        }
    }
    public function updatePass($userId,$pass){
        $result=$this->db->query("UPDATE member SET password='".$pass."' WHERE memberId='".$userId."'");
        if($result>0){
            return true;
        }else{
            return false;
        }
    }
    
    //check email đã tồn tại chưa.
    public function checkMail($email){
        $result=$this->db->query("SELECT * FROM member WHERE email='".$email."'");
        if($result){
            return false;
        }else{
            return true;
        }
    }
    //check userNam da ton tai chua.
    public function checkUserName($username){
        $result=$this->db->query("SELECT * FROM member WHERE userName='".$username."'");
        if($result){
            return false;
        }else{
            return true;
        }
    }
    public function register($userName,$passWord,$email){
        $result=$this->db->query("INSERT INTO member(userName,PASSWORD,email) VALUE('".$userName."','".$passWord."','".$email."')");
        if($result>0){
            return true;
        }else{
            return false;
        }
    }
    public function checkPass($password){
        $result=$this->db->query("SELECT * FROM member WHERE password='".$password."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
}
