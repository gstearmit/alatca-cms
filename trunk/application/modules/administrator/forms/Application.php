<?php 
class Administrator_Form_Application extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("ApplicationForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
       
        $modelNewstag= new HT_Model_administrator_models_newstag();
        $dataNewsTag = $modelNewstag->getAll("active = 1 and is_feature = 1 order by tag_name asc");
        foreach($dataNewsTag as $vTag):
            $dataTag["{$vTag['id']}"] = $vTag['tag_name'];
        endforeach;
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Tên ứng dụng')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được chống!'));
        $name->setAttrib('size', '70');
        
        
        $link = new Zend_Form_Element_Text('link');
        $link->setLabel('Link')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được chống!'));
        $link->setAttrib('size', '70');
     
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Hình ảnh')
              ->setDestination(APPLICATION_PATH ."/../public/uploads")
              ->setRequired(true)
              ->setDescription('Nhấn vào nút browse để upload hình ảnh');
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 10240000);            // limit to 10 meg        
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, 
        
        
        $desc = new Zend_Form_Element_Textarea('description');
        $desc->setLabel('Mô tả')
                 ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được trống!'));
        $desc->setAttrib('rows', '5');
        
        
        $screenshot = new Zend_Form_Element_Textarea('screenshot');
        $screenshot->setLabel('Giới thiệu');
        $screenshot->setAttrib('rows', '5');
        
        
        
        $tagsearch = new Zend_Form_Element_Textarea('tag_search');
        $tagsearch->setLabel('Tag');
        $tagsearch->setAttrib('rows', '10');
        
        // iOS Link
        $iosLink = new Zend_Form_Element_Text('ios_link');
        $iosLink->setLabel('IOS Link');
        $iosLink->setAttrib('size', '70');
        
        // Android Link
        $androidLink = new Zend_Form_Element_Text('android_link');
        $androidLink->setLabel('Android Link');
        $androidLink->setAttrib('size', '70');
        
        // Facebook Link
        $fbLink = new Zend_Form_Element_Text('fb_link');
        $fbLink->setLabel('Facebook Link');
        $fbLink->setAttrib('size', '70');
        
     
        // Status field
        $status = $this->createElement('select','status');
        $status ->setLabel('Status:')->addMultiOptions(array('public' => 'Public','unpublic' => 'Unpublic'));
        
        
        //$countries = new Countries();
        $appCats = new HT_Model_administrator_models_application();
        $appCatList = $appCats->getAllAppCat();

        $category = new Zend_Form_Element_Select('category_id');

        $category ->setLabel('Category:')->addMultiOptions($appCatList);
        
        $news_tag = new Zend_Form_Element_MultiCheckbox('tags');
        $news_tag->setLabel("Feature");
        $news_tag->setMultiOptions($dataTag);
        
		//Service
		$mService = new HT_Model_administrator_models_news();
		
		$dataService = $mService->getServiceFromSer_phase();
        foreach($dataService as $vService):
            $dataOptionService["{$vService['id']}"] = $vService['name'];
        endforeach;
        $service = new Zend_Form_Element_MultiCheckbox('ser_phase_id');
        $service->setLabel("Dịch vụ");
        $service->setMultiOptions($dataOptionService);		
		
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
		
        $this->addElements(array($name, $category ,$link, $image, $desc,$screenshot, $tagsearch, $iosLink, $androidLink, $fbLink, $status,  $service, $ser_action, $news_tag, $submit)); 
        
    }  
}
