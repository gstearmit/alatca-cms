<?php 
class Administrator_Form_Appcat extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("AppcatForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
       
        
        $name = new Zend_Form_Element_Text('cate_name');
        $name->setLabel('Tên danh mục:')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được chống!'));
        $name->setAttrib('size', '70');
        
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        $this->addElements(array($name, $submit));                                           
    }  
}
