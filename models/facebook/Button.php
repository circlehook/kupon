<?php
namespace app\models\facebook;

class Button{

    public $type;
    public $title;
    public $url;
    public $payload;
    public $webview_height_ratio = null;
    public $button;
    
    /*
    * Example input button:
	*	['title' => 'button1', 'type' => 'text', 	  'payload' => 'yes']
	*	['title' => 'button2', 'type' => 'postback',  'payload' => 'search']
	*	['title' => 'button3', 'type' => 'web_url',   'url' 	=> 'https://fb.com']
	*
	*	+ 'webview_height_ratio' => 'full' for buttons menu
  *  $target != null if must set payload
	*/
	public function __construct($content, $target = null){
		
		$this->type 	= $content['type'];
        $this->title 	= $content['title'];
		
		if($target){
    		switch($this->type){
    			case 'text':
	            	$this->payload = $target;
	            	break;
	            case 'postback':
	            	$this->payload = $target;
	            	break;
	            case 'web_url':
		            $this->url = $target;
		            break;
    		}
    	}else{
			switch($this->type){
	            case 'text':
	            	$this->payload = $content['payload'];
	            	break;
	            case 'postback':
		            $this->payload = $content['payload'];
		            break;
	            case 'web_url':
	                $this->url  = $content['url'];
					break;
	        }
    	}
		
        if ($content['webview_height_ratio'] != '') {
            $this->webview_height_ratio = $content['webview_height_ratio'];
        }

		$this->makeButton();
		
	}

	public function getReady(){
		return $this->button;
	}

	public function makeButton(){

		$this->button['title'] 		= $this->title;

		switch($this->type){
		    case 'text':
		    	$this->button['content_type'] 	= 'text';
		    	$this->button['payload'] 		= $this->payload;
				break;
		    case 'postback':
		    	$this->button['type'] 			= 'postback';
		    	$this->button['payload'] 		= $this->payload;
		    	break;
		    case 'web_url':
		    	$this->button['type'] 			= 'web_url';
		    	$this->button['url'] 			= $this->url;
		        break;
		}
		if($this->webview_height_ratio != '')
			$this->button['webview_height_ratio'] = $this->webview_height_ratio;

	}

	public static function completeContent($buttons, $complete){
		$buttonsContent = [];
		$id  			= $complete['id'];       
        $url 			= $complete['url'];
        $payload 		= $complete['payload'];
        $next 			= $complete['next'];
        $more 			= $complete['more'];

        foreach ($buttons as $key => $content) {

	    	if($content['option'] == 'next' && $next){
        		$content['payload'] = $next;
        	}else{
        		
	        	if($content['option'] == 'more' && $more){
		    		$content['payload'] = $more;
				}else{
					if($content['type'] == 'web_url' && !($content['url'])){
		        		$content['url'] = $url;
		        	}elseif($content['type'] == 'postback' && !$content['payload']){
		        		$content['payload'] = $payload;
		        	}
		    	}
        	}


        	$buttonsContent[] = $content;
        }
        return $buttonsContent;
	}

	public static function makeMultiplePayload($button, $items){
		return [
				'title' 	=> $button['title'], 	
				'type' 		=> $button['type'], 
				'payload' 	=> $buttons['suffix'].implode('_',$items)
		];
	}

}



