<?php
namespace app\models\facebook;

use app\models\facebook\Button;

class Element{

	public $title;
	public $subtitle;
	public $image_url;
	public $url;
	public $buttons;
	
	public $element;


	public function __construct($content, $buttons = []){
		$this->title 		= $content['title'];
        $this->subtitle 	= $content['description'];
        $this->image_url 	= $content['image'];
        $this->url 			= $content['url'];
        /*if($target){Content
        	$this->target 	= $target;
        }*/
        $this->buttons      = $buttons;
        $this->makeElement();
	}

	public function getReady(){
		return $this->element;
	}


	/*public function makeButtons(){
	    foreach ($this->buttons as $key => $buttonData) {
	    	if($this->target){
	    		$button = new Button($buttonData, $this->target);
	    	}else{
	    		$button = new Button($buttonData);
	    	}
	    	$this->readyButtons[] = $button->getReady();
	    }
	}*/

	/* 
    * создаёт элемент для вложения
    */
 	public function makeElement(){
        
	    $this->element = [
	        'title'     =>  $this->title,
	        'subtitle'  =>  $this->subtitle,
	        'image_url' =>  $this->image_url,
	        'buttons'   =>  $this->buttons  

	      ];

  	}

}