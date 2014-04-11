<?php 
class Administrator_Form_CouponImages extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("CouponImagesForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));        
        $dataCoupons = array();                            
        $modelCoupons = new HT_Model_administrator_models_coupons();
        $dataAllCoupons = $modelCoupons->getAll("1=1");
        foreach($dataAllCoupons as $coupon):
            $dataCoupons["{$coupon['id']}"] = $coupon['coupon_name'];
        endforeach;                                                                                
        
        $coupon_id = new Zend_Form_Element_Select('coupon_id');
        $coupon_id->setLabel('Coupon')
                ->setRequired(true)
                ->setMultiOptions($dataCoupons);
        $coupon_id->setValue("");
        
        $coupon_image = new Zend_Form_Element_File('coupon_image');
        $coupon_image->setLabel('Hình ảnh')
              ->setDestination(APPLICATION_PATH ."/../public/uploads/news")
              ->setRequired(true)              
              ->setDescription('Nhấn vào nút browse để upload hình ảnh');
        $coupon_image->addValidator('Count', false, 1);                // ensure only 1 file
        $coupon_image->addValidator('Size', false, 10240000);            // limit to 10 meg
        $coupon_image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG,                 
                                                        
                                                                                
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        $this->addElements(array($coupon_id,$coupon_image, $submit ));
    }  
}
