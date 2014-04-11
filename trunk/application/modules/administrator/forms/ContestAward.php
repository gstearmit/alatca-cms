<?php 
class Administrator_Form_ContestAward extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("ContestAwardForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));                                                
        
        for($i=1;$i<13;$i++){
            $dataWeek[$i] = $i;
        }        
        
        $week = new Zend_Form_Element_Select('week');
        $week->setLabel('Tuần thi')
              ->setMultiOptions($dataWeek);
        $week->setValue("");               
        
        $award = new Zend_Form_Element_Textarea('content');
        $award->setLabel('Nội dung');        
        $award->setAttrib('rows', '3');               
							
							                                                    
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        $this->addElements(array($week, $award, $submit ));
    }  
}
