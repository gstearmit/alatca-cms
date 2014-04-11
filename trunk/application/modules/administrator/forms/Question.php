<?php 
class Administrator_Form_Question extends Zend_Form{
    
   public function init(){
        $this->setMethod("post");
		$this->setName("QuestionForm");
        $this->setAttrib('autocomplete', 'off');	        	

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
        $modelFaqcat = new HT_Model_administrator_models_faqcat();
        $dataAllFaqcat = $modelFaqcat->getAll("1 = 1 order by stt asc");
        foreach($dataAllFaqcat as $vCat):
            $idCat = $vCat['id'];
            $dataFaqCat[$idCat] = $vCat['name'];
        endforeach;
        
        $modelNewstag= new HT_Model_administrator_models_newstag();
        $dataNewsTag = $modelNewstag->getAll("active = 1 and is_feature = 1 order by tag_name asc");
    
        foreach($dataNewsTag as $vTag):
            $dataTag["{$vTag['id']}"] = $vTag['tag_name'];
        endforeach;
        
        $dataFaqCat[""]  = "--chọn--";
        $faq_cat_id = new Zend_Form_Element_Select('faq_cat_id');
        $faq_cat_id->setLabel('Thể loại')
              ->setRequired(true)
              ->addValidator('NotEmpty',true,array('messages' => 'Phải chọn một thể loại!'))
              ->setMultiOptions($dataFaqCat);
        $faq_cat_id->setValue("");
        
        
        $content = new Zend_Form_Element_Textarea('content');
        $content->setLabel('Câu hỏi')
                 ->setRequired(true)
                 ->addValidator('NotEmpty',true,array('messages' => 'Không được trống!'));
        $content->setAttrib('rows', '8');
        
        $isquestion = new Zend_Form_Element_Hidden('is_question');
        $isquestion->setValue('1');
        
        $time = time();
        $time_post = new Zend_Form_Element_Hidden('time');
        $time_post->setValue($time);
        
        $status = new Zend_Form_Element_Hidden('status');
        $status->setValue('public');
        
        $user_post = new Zend_Form_Element_Hidden('user_post');
        $user_post->setValue('none');
        
        $user_name = new Zend_Form_Element_Hidden('user_name');
        $user_name->setValue('wishhoidap');
        
        $news_tag = new Zend_Form_Element_MultiCheckbox('tags');
        $news_tag->setLabel("Feature");
        $news_tag->setMultiOptions($dataTag);
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $submit->class = "bt-save";
        
        $this->addElements(array($faq_cat_id, $content, $news_tag, $submit, $isquestion, $time_post, $status, $user_post, $user_name
        ));                                           
    }  
}
