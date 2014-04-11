<?php 
class Administrator_Form_Contest extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("ContestForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));                                                
        
        $rules = new Zend_Form_Element_Textarea('rules');
        $rules->setLabel('Thể lệ');
        $rules->setAttrib('rows', '3');
        
        $award = new Zend_Form_Element_Textarea('award');
        $award->setLabel('Giải thưởng');        
        $award->setAttrib('rows', '3');               
							
							                                                    
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        $this->addElements(array($rules, $award, $submit ));
    }  
}
