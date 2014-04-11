<?php 
class Administrator_Form_ListTimeAppointment extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("ListTimeAppointmentForm");
        $this->setAttrib('autocomplete', 'off');	        	
        
        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
       
        //list partner
        $modelListappointment = new HT_Model_administrator_models_listappointment();
        $listListappointment = $modelListappointment->getAllExentsion("1=1 order by id desc");
        foreach($listListappointment as $value):
            $dataListappointment["{$value['id']}"] = $value['partner_name']." - ".$value['name'];
        endforeach;
        
        
        $list_appointment_id = new Zend_Form_Element_Select('list_appointment_id');
        $list_appointment_id->setLabel('Phiếu hẹn')
              ->setMultiOptions($dataListappointment);
      
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Tên hiển thị')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được chống!'));
        $name->setAttrib('size', '70');
        
        $date = new Zend_Form_Element_Text('time');
        $date->setLabel('Thời gian');
        $date->setAttrib('size', '70');
       
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        $this->addElements(array($list_appointment_id, $name, $date, $submit 
        ));                                           
    }  
}
