<?php 
class Administrator_Form_News extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("NewsForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
        $modelSertime = new HT_Model_administrator_models_sertime();
        $dataAllSertime = $modelSertime->getAll("phase_id = '2' order by time_value asc");
        foreach($dataAllSertime as $vTime):
            $dataSertime["{$vTime['id']}"] = $vTime['time_value'] . " " . $vTime['name'];
        endforeach;
       
        $dataSertime[""]  = "--chọn--";
        
        
        $modelNewstag= new HT_Model_administrator_models_newstag();
        $dataNewsTag = $modelNewstag->getAll("active = 1 order by tag_name asc");
        foreach($dataNewsTag as $vTag):
            $dataTag["{$vTag['id']}"] = $vTag['tag_name'];
        endforeach;
        
        $dataNewsTag2 = $modelNewstag->getAll("active = 1 and is_feature = 1 order by tag_name asc");
        foreach($dataNewsTag2 as $vTag2):
            $dataTag2["{$vTag2['id']}"] = $vTag2['tag_name'];
        endforeach;
        
        $modelCat= new HT_Model_administrator_models_category();
        $dataCat = $modelCat->getAll("active = 1 order by stt asc");
        foreach($dataCat as $vCat):
            $dataOptionCat["{$vCat['id']}"] = $vCat['category_name'];
        endforeach;
        
        
        $modelLocation = new HT_Model_administrator_models_location();
        $listDataLocation = $modelLocation->getAll("is_partner = 1");
        foreach($listDataLocation as $vLo):
        $dataListLocation["{$vLo['location_id']}"] = $vLo['location_name'];
        endforeach;
        $dataListLocation["0"] = "Chọn location";
        
        
        
        $title_vn = new Zend_Form_Element_Text('title_vn');
        $title_vn->setLabel('Tiêu đề VN')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,array('messages' => 'Không được chống!'));
        $title_vn->setAttrib('size', '70');
        
        $title_en = new Zend_Form_Element_Text('title_en');
        $title_en->setLabel('Tiêu đề EN');
        $title_en->setAttrib('size', '70');
        
        $image = new Zend_Form_Element_File('pictrue');
        $image->setLabel('Hình ảnh')
              ->setDestination(APPLICATION_PATH ."/../public/uploads/news")
              ->setRequired(true)
              
              ->setDescription('Nhấn vào nút browse để upload hình ảnh');
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 10240000);            // limit to 10 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, 
        
        
        $desc_vn = new Zend_Form_Element_Textarea('desc_vn');
        $desc_vn->setLabel('Mô tả VN')
                 ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được trống!'));
        $desc_vn->setAttrib('rows', '5');
        
        $desc_en = new Zend_Form_Element_Textarea('desc_en');
        $desc_en->setLabel('Mô tả EN');
        $desc_en->setAttrib('rows', '5');
        
        
        
        $content_vn = new Zend_Form_Element_Textarea('content_vn');
        $content_vn->setLabel('Nội dung VN')
                 ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được trống!'));
        $content_vn->setAttrib('rows', '8');
        
        
        $content_en = new Zend_Form_Element_Textarea('content_en');
        $content_en->setLabel('Nội dung EN');
        $content_en->setAttrib('rows', '8');
        
        $content_mobile_vn = new Zend_Form_Element_Textarea('content_mobile_vn');
        $content_mobile_vn->setLabel('Nội dung Mobile');
        $content_mobile_vn->setAttrib('rows', '8');
        
        $created = new Zend_Form_Element_Text('created');
        $created->setLabel('Ngày tạo');
        $created->setValue(date('Y-m-d h:i:s'));
        
        $slide = new Zend_Form_Element_Checkbox('slide');
        $slide->setLabel('Slide')
              ->addFilter('StringToLower');
              
        $is_login = new Zend_Form_Element_Checkbox('must_login');
        $is_login->setLabel('Phải login')
              ->addFilter('StringToLower');

        $ser_time = new Zend_Form_Element_Select('time_id');
        $ser_time->setLabel('Chọn thời gian')
              ->setMultiOptions($dataSertime);
        $ser_time->setValue("");
        
        $location_id = new Zend_Form_Element_Select('location_id');
        $location_id->setLabel('Chọn Location')
              ->setMultiOptions($dataListLocation);
        $location_id->setValue("0");
        
        $tagsearch = new Zend_Form_Element_Textarea('tag_search');
        $tagsearch->setLabel('Tag');
        $tagsearch->setAttrib('rows', '10');
        
        $note_data = new Zend_Form_Element_Textarea('note_data');
        $note_data->setLabel('Giới thiệu đối tác');
        $note_data->setAttrib('rows', '5');
        
        $note_link = new Zend_Form_Element_Text('note_link');
        $note_link->setLabel('Link đối tác');
        $note_link->setAttrib('size', '120');
        
        
        $news_tag = new Zend_Form_Element_MultiCheckbox('news_tag');
        $news_tag->setLabel("Tags News");
        $news_tag->setMultiOptions($dataTag);

        $news_tag2 = new Zend_Form_Element_MultiCheckbox('tags');
        $news_tag2->setLabel("Feature");
        $news_tag2->setMultiOptions($dataTag2);
        
        $category = new Zend_Form_Element_MultiCheckbox('category_name');
        $category->setLabel("Danh mục tin")
        ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được trống!'));
        $category->setMultiOptions($dataOptionCat);
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
		
        $active = new Zend_Form_Element_Checkbox('status',array("checked" => "checked"));
        $active->setLabel('Kích hoạt tin')
              ->addFilter('StringToLower');
			  
		
		//Service
		$mService = new HT_Model_administrator_models_news();
		
		$dataService = $mService->getServiceFromSer_phase();
        foreach($dataService as $vService):
            $dataOptionService["{$vService['id']}"] = $vService['name'];
        endforeach;
        $service = new Zend_Form_Element_MultiCheckbox('service');
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
					 
					 
        $this->addElements(array($title_vn, $title_en, $image, $active, $ser_time, $desc_vn, $desc_en, $content_vn, $content_en, $content_mobile_vn, $slide, $is_login, $news_tag, $news_tag2, $service, $ser_action,$category, $created, $location_id, $tagsearch, $note_data,$note_link,$submit 
        ));                                           
    }  
}
