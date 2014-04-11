<?php 
class Administrator_Form_Partners extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("PartnersForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));        
                                                      
        $modelLocation = new HT_Model_administrator_models_location();
        $listLocation = $modelLocation->fetchAll("is_partner = 1");
        
        foreach($listLocation as $value):
            $dataLocation["{$value['location_id']}"] = $value['location_name'];
        endforeach;
        
        $category = new Zend_Form_Element_Select('location_id');
        $category->setLabel('Location')
              ->setMultiOptions($dataLocation);
        
        $partner_name = new Zend_Form_Element_Text('partner_name');
        $partner_name->setLabel('Tên đối tác')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được để trống!'));
        $partner_name->setAttrib('size', '70'); 
        
        $rate = new Zend_Form_Element_Text('rate');
        $rate->setLabel('Đánh giá (1->5)');
        $rate->setAttrib('size', '10'); 
        
        $link = new Zend_Form_Element_Text('link');
        $link->setLabel('Link detail');
        $link->setAttrib('size', '70');       
                                           
        $user_name = new Zend_Form_Element_Text('user_name');
        $user_name->setLabel('Username')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được để trống!'));
        $user_name->setAttrib('size', '70');
        
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Logo')
              ->setDestination(APPLICATION_PATH ."/../public/uploads")
              ->setRequired(true)
              ->setDescription('Nhấn vào nút browse để upload hình ảnh');
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 10240000);            // limit to 10 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, 
        $image->getDecorator('Description')->setOption('escape', false);
        
        $active = new Zend_Form_Element_Checkbox('partner_status',array("checked" => "checked"));
        $active->setLabel('Trạng thái')
              ->addFilter('StringToLower');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        $this->addElements(array($category, $partner_name,$user_name, $image, $rate , $link, $active, $submit ));
    }  
}
