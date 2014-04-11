<?php 
class Administrator_Form_Coupons extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("CouponsForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));        
        $dataPartners = array();                            
        $modelPartners = new HT_Model_administrator_models_partners();
        $dataAllPartners = $modelPartners->getAll("partner_status = 1 order by partner_name asc");
        foreach($dataAllPartners as $partner):
            $dataPartners["{$partner['id']}"] = $partner['partner_name'];
        endforeach;                                                                
        
        $coupon_name = new Zend_Form_Element_Text('coupon_name');
        $coupon_name->setLabel('Tên ưu đãi')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được để trống!'));
        $coupon_name->setAttrib('size', '70');
        
        $coupon_price = new Zend_Form_Element_Text('coupon_price');
        $coupon_price->setLabel('Giá')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được để trống!'));
        $coupon_price->setAttrib('size', '20');
        
        $coupon_start_date = new Zend_Form_Element_Text('coupon_start_date');
        $coupon_start_date->setLabel('Ngày bắt đầu')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được để trống!'));
        $coupon_start_date->setAttrib('size', '20');
        
        $coupon_end_date = new Zend_Form_Element_Text('coupon_end_date');
        $coupon_end_date->setLabel('Ngày kết thúc')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được để trống!'));
        $coupon_end_date->setAttrib('size', '20');
        
        $partner_id = new Zend_Form_Element_Select('partner_id');
        $partner_id->setLabel('Đối tác')
                ->setRequired(true)
                ->setMultiOptions($dataPartners);
        $partner_id->setValue("");
        
        $coupon_image = new Zend_Form_Element_File('coupon_image');
        $coupon_image->setLabel('Hình ảnh')
              ->setDestination(APPLICATION_PATH ."/../public/uploads/news")              
              ->setDescription('Nhấn vào nút browse để upload hình ảnh');
        $coupon_image->addValidator('Count', false, 1);                // ensure only 1 file
        $coupon_image->addValidator('Size', false, 10240000);            // limit to 10 meg
        $coupon_image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG,                         
        
        $coupon_description = new Zend_Form_Element_Textarea('coupon_description');
        $coupon_description->setLabel('Mô tả')
                 ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được để trống!'));
        $coupon_description->setAttrib('rows', '3');
        
        $coupon_sms_content = new Zend_Form_Element_Textarea('coupon_sms_content');
        $coupon_sms_content->setLabel('Nội dung SMS')
                 ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được để trống!'));        
        $coupon_sms_content->setAttrib('rows', '3');
        
        $coupon_term = new Zend_Form_Element_Textarea('coupon_term');
        $coupon_term->setLabel('Điều kiện')
                 ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được để trống!'));
        $coupon_term->setAttrib('rows', '5');
        
        $coupon_info = new Zend_Form_Element_Textarea('coupon_info');
        $coupon_info->setLabel('Thông tin')
                 ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được để trống!'));
        $coupon_info->setAttrib('rows', '5');
                                   
		//Service
		$mService = new HT_Model_administrator_models_news();
		
		$dataService = $mService->getServiceFromSer_phase();
        foreach($dataService as $vService):
            $dataOptionService["{$vService['id']}"] = $vService['name'];
        endforeach;
        $service = new Zend_Form_Element_MultiCheckbox('service');
        $service->setLabel("Dịch vụ");
        $service->setMultiOptions($dataOptionService);
											                        
		//Feature        
        $modelNewstag= new HT_Model_administrator_models_newstag();
        $dataNewsTag2 = $modelNewstag->getAll("active = 1 and is_feature = 1 order by tag_name asc");
        foreach($dataNewsTag2 as $vTag2):
            $dataTag2["{$vTag2['id']}"] = $vTag2['tag_name'];
        endforeach;
        $news_tag2 = new Zend_Form_Element_MultiCheckbox('feature');
        $news_tag2->setLabel("Feature");
        $news_tag2->setMultiOptions($dataTag2);		
		
		//Action
		$mAction = new HT_Model_administrator_models_news();
		$dataAction = $mAction->getActionsFromSerAction();
		foreach ($dataAction as $key => $value) {
			$dataOptionAction["{$value['id']}"] = $value['name'];
		}
		$ser_action = new Zend_Form_Element_MultiCheckbox('ser_action');
		$ser_action->setLabel("Hành động");
		$ser_action->setMultiOptions($dataOptionAction);		                    
							
							                                                    
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        $this->addElements(array($coupon_name, $coupon_price,$partner_id,$coupon_image, $coupon_description,$coupon_sms_content,$coupon_term,$coupon_info,$news_tag2, $service, $ser_action, $coupon_start_date,$coupon_end_date, $submit ));
    }  
}
