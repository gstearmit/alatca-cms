<?php 
class Administrator_Form_HiddenAppoint extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("HiddenAppointForm");
        $this->setAttrib('autocomplete', 'off');	        	
        
        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
       
       
        
        $name = new Zend_Form_Element_Text('fullname');
        $name->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không du?c ch?ng!'));
        
        
        $email = new Zend_Form_Element_Text('email');
        $email->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không du?c ch?ng!'));
        $email->setAttrib('size', '70');
        
        $phone = new Zend_Form_Element_Text('mobile');
        $phone->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không du?c ch?ng!'));
        $phone->setAttrib('size', '70');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        $this->addElements(array($name, $email, $phone, $submit 
        ));                                           
    }  
}
