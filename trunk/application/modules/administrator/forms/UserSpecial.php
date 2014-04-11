<?php 
class Administrator_Form_UserSpecial extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("UserSpecialForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Tên định danh')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được chống!'));
        $name->setAttrib('size', '70');
        
        $user_name = new Zend_Form_Element_Text('user_name');
        $user_name->setLabel('Username');
        $user_name->setAttrib('size', '70');
        
        $background = new Zend_Form_Element_Text('background');
        $background->setLabel('Mầu background');
        $background->setAttrib('size', '70');
        
        $linkpartner = new Zend_Form_Element_Text('link_partner');
        $linkpartner->setLabel('Link partner');
        $linkpartner->setAttrib('size', '70');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        $this->addElements(array($name, $user_name, $background, $linkpartner,$submit 
        ));                                           
    }  
}
