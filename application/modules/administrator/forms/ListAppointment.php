<?php 
class Administrator_Form_ListAppointment extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("ListAppointmentForm");
        $this->setAttrib('autocomplete', 'off');	        	
        
        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
       
        $dataForm['name']       = "Name";
        $dataForm['address']    = "Address";
        $dataForm['phone']    = "Phone";
        $dataForm['email']      = "Email";
        $dataForm['date']       = "Date";
        $dataForm['time']       = "Time";
        $dataForm['note']       = "Note";
        $dataForm['vacine']       = "Vacine";
        //list data cat
        $modelListAppointment = new HT_Model_administrator_models_listappointment();
        $listDataCat = $modelListAppointment->listCatAppoint();
        foreach($listDataCat as $vC):
            $dataCat["{$vC['id']}"] = $vC['name'];
        endforeach;
        
        //list partner
        $modelPartner = new HT_Model_administrator_models_partners();
        $listPartner = $modelPartner->getAll("1=1 order by partner_name desc");
        foreach($listPartner as $value):
            $dataPartner["{$value['id']}"] = $value['partner_name'];
        endforeach;
        
        $category = new Zend_Form_Element_Select('category_id');
        $category->setLabel('Thể loại')
              ->setMultiOptions($dataCat);
        $category->setValue("");
        
        $partners = new Zend_Form_Element_Select('partner_id');
        $partners->setLabel('Của đối tác')
              ->setMultiOptions($dataPartner);
        $partners->setValue("");
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Tên chương trình')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được chống!'));
        $name->setAttrib('size', '70');
        
        
       
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Hình ảnh')
              ->setDestination(APPLICATION_PATH ."/../public/uploads")
              ->setRequired(true)
              ->setDescription('Nhấn vào nút browse để upload hình ảnh');
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 10240000);            // limit to 10 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, 
        $image->getDecorator('Description')->setOption('escape', false);
        
        $desc = new Zend_Form_Element_Textarea('description');
        $desc->setLabel('Mô tả');
        $desc->setAttrib('rows', '5');
        
        
        
        
        
        $list_form = new Zend_Form_Element_MultiCheckbox('list_form');
        $list_form->setLabel("Danh sách Form");
        $list_form->setMultiOptions($dataForm);
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        $this->addElements(array($partners, $category, $name, $image ,$desc, $list_form, $submit 
        ));                                           
    }  
}
