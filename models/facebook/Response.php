<?php
namespace app\models\facebook;

use app\models\facebook\Element;
use app\models\facebook\Button;

class Response{

	public $user_id;
	public $message;
	public $response;
	public $attachment;

	/*
    * Example input button:
	* ['title' => 'button1', 'type' => 'text',    'payload' => 'yes']
	* ['title' => 'button2', 'type' => 'postback',  'payload' => 'search']
	* ['title' => 'button3', 'type' => 'web_url',   'url'   => 'https://fb.com']
	*
	* + 'webview_height_ratio' => 'full' for buttons menu
	*/
	public $buttonsContent          = [];
  public $buttons                 = [];
	/*
	* const  TEMPLATE_BUTTONS    = [
	*    0 => ['title' => Config::URL_BUTTON_TITLE,          'type' => 'postback' , 'option' => 'more'],
	*    1 => ['title' => Config::URL_BUTTON_DETAIL_TITLE,   'type' => 'postback', 'option' => 'next'],
	*    
	*  ];
	* 
	*/
	public $buttonsTemplate         = [];
	
	public $elementsContent         = [];
  public $elements                = [];

	
	/* 
	формирует запрос для отправки сообщения
	*/
	public function callMessage(){
		$this->response = [       //данные сообщения
		   'recipient' => [
		       'id' => $this->user_id        
		   ],
		   'message' => [
		       'text' => $this->message
		   ]
		];
	}

  /* 
  формирует запрос для отправки рекламного сообщения без ответа
  */
  public function callUpdateMessage(){
    $this->response = [       //данные сообщения
       'messaging_type' => 'UPDATE',
       'recipient' => [
           'id' => $this->user_id        
       ],
       'message' => [
           'text' => $this->message
       ]
    ];
  }

  /* 
  
  */
  public static function staticCallEventTagMessage($user_id, $message){
    return [       //данные сообщения
       'recipient' => [
           'id' => (string) $user_id        
       ],
       'message' => [
           'text' => (string) $message
       ],
       'messaging_type' => 'MESSAGE_TAG',
       'tag'            => 'CONFIRMED_EVENT_UPDATE'
    ];
  }

	public function callGetStarted(){
	    $this->response = [       
	       'get_started' => [
	           'payload' => 'get_started'        
	       ]
	    ];
	}

	public function callPersistentMenu(){
	    
	    $this->response = [       
	       'persistent_menu' => [
	          [
	              'locale'                  => 'default',
	              'composer_input_disabled' => false,
	              'call_to_actions'         => $this->buttons
	          ]
	      ]
	    ];
	}
	/* 
  формирует запрос для отправки вложения
  */
  public function callAttachment(){
    $this->response = [       
       'recipient' => [
           'id' => $this->user_id        
       ],
       'message' => [
           'attachment' => $this->attachment
           
       ]
    ];
  }

  /*
    шаблон вложений
  */
  public function makeGenericAttachments(){
    $this->attachment =  [
        'type' => 'template',
        'payload' => [
          'template_type' =>  'generic',
          'elements'    =>  $this->elements
        ]
    ];
    
  }

  /*
    сообщение с кнопкой 
   */
  public function makeButtonAttachment(){
    $this->attachment =  [
        'type' => 'template',
        'payload' => [
          'template_type' =>  'button',
          'text'          =>   $this->message, 
          'buttons'       =>   $this->buttons
        ]
    ];
  }

    /* 
    создаёт элемент вложения основного меню
  */
  public function makeMainMenuAttachment($name, $items){
    $this->elements[] = [
        'title'     =>  $name,
        'buttons'   =>  $this->elements 
    ];
  }

    /* 
    создаёт элемент вложения основного меню
  */
  public function makeMainMenuElement($name, $items){
    $this->attachment = [
        'title'     =>  $name,
        'buttons'   =>  $this->elements 
    ];
  }

  /* 
    формирует запрос для отправки быстрых ответов
  */
  public function callQuickReply(){

    $this->response = [       
       'recipient' => [
           'id' => $this->user_id        
       ],
       'messaging_type' => 'RESPONSE',
       'message' => [
           'text' 			=> $this->message, //Config::BUTTONS_SMILE,
           'quick_replies'	=> $this->buttons 
       ]
    ];
  }
  /* 
  формирует запрос для отправки кнопок
  */
  public function callButtons(){

    $this->response = [       
       'recipient' => [
           'id' => $this->user_id        
       ],
       'messaging_type' => 'RESPONSE',
       'message' => [
           'text' 			=> $this->message,
           'quick_replies' 	=> $this->buttons 
       ]
    ];
   }
  



}