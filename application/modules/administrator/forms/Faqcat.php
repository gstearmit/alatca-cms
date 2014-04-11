<?php 
class Administrator_Form_Faqcat extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("FaqcatForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
        
        $title = new Zend_Form_Element_Text('name');
        $title->setLabel('Tên danh mục')
                 ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được trống!'));
        $title->setAttrib('size', '60');
        
        $order = new Zend_Form_Element_Text('stt');
        $order->setLabel('Số thứ tự');
        $order->setAttrib('size', '10');
        
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Hình ảnh')
              ->setDestination(APPLICATION_PATH ."/../public/uploads")
              ->setRequired(true)
              
              ->setDescription('Nhấn vào nút browse để upload hình ảnh');
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 10240000);            // limit to 10 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, 
        $image->getDecorator('Description')->setOption('escape', false);
        
        $time = new Zend_Form_Element_Hidden('time');
        $time->setValue(time());
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        
        $this->addElements(array($title, $image, $order, $submit, $time
        ));                                           
    }  
}
