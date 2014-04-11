<?php 
class Administrator_Form_Advertising extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("AdvertisingForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
        
        $image = new Zend_Form_Element_File('url');
        $image->setLabel('Hình ảnh')
              ->setDestination(APPLICATION_PATH ."/../public/uploads/adv")
              ->setRequired(true)
              //->setMaxFileSize(10240000) // limits the filesize on the client side
              ->setDescription('Nhấn vào nút browse để upload hình ảnh');
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 10240000);            // limit to 10 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, 
        
        $link = new Zend_Form_Element_Text('link');
        $link->setLabel('Link')
                 ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được trống!'));
        $link->setAttrib('size', '60');
        
        $alt = new Zend_Form_Element_Text('alt');
        $alt->setLabel('Alt');
        $alt->setAttrib('size', '60');
        
        
        $time = new Zend_Form_Element_Text('time');
        $time->setLabel('Ngày hết hạn');
        $time->setAttrib('time', '60');
        
        $model = new HT_Model_administrator_models_advertising();
        $dataPosition = $model->listPosition();
        
        $dataPosition[""]  = "--chọn vị trí--";
        $type = new Zend_Form_Element_Select('type');
        $type->setLabel('Thể loại')
              ->setRequired(true)
              ->addValidator('NotEmpty',true,array('messages' => 'Phải chọn một vị trí!'))
              ->setMultiOptions($dataPosition);
        $type->setValue("");
        
        $default = new Zend_Form_Element_Checkbox('default');
        $default->setLabel('Default');
     
        
        $user_name = new Zend_Form_Element_Hidden('user_name');
        $user_name->setValue('wishhoidap');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        
        $this->addElements(array($image, $link, $alt, $time, $type, $default, $submit
        ));                                           
    }  
}
