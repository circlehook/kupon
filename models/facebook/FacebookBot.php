<?php
namespace app\models\facebook;


use app\models\facebook\Response;
//use app\models\db\Logs;
use app\models\facebook\Config;
use app\models\facebook\Element;
use app\models\facebook\Button;

class FacebookBot extends Response{


  public $params          = [];
  public $payloadNextCard = null;
  private $apiResponse    = [];
  
  protected $baseApiUrl = "https://graph.facebook.com/";
  protected $apiVersion = "v8.0/me/";
  protected $apiUrl     = null;
  protected $token      = null;
  
  public function __construct($token, $user_id)
  {   
      $this->user_id  = $user_id;
      $this->token    = $token;
      $this->apiUrl   = $this->baseApiUrl . $this->apiVersion;
      
  }

  /* 
    send request 
    отправляет сформированный запрос $this->request на API 
  */
  public function send($method){ 
   
     //$url   = "https://graph.facebook.com/v4.0/me/".$method."?access_token=" . $this->token; 
     $url         = $this->apiUrl .$method."?access_token=" . $this->token;
     $data_string = json_encode($this->response);   //данные конвертируем в json формат
     //Log::saveToFile($url.'/r/n', Config::PATH_FB_LOG);
     //Logs::saveToFile($data_string, Config::PATH_FB_LOG);
   
     $ch = curl_init($url); 
     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
     curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
             'Content-Type: application/json',
             'Content-Length: ' . strlen($data_string))
     );
   
     $return = curl_exec($ch);
     $this->apiResponse = json_decode($return, true);
     curl_close($ch);
  }

  public static function staticSend($response){ 

    //$url   = "https://graph.facebook.com/v4.0/me/".$method."?access_token=" . $this->token; 
    $url         = "https://graph.facebook.com/v5.0/me/messages?access_token=" . Config::TOKEN;
    $data_string = json_encode($response);   //данные конвертируем в json формат
    //Log::saveToFile($url.'/r/n', Config::PATH_FB_LOG);
    //Logs::saveToFile($data_string, Config::PATH_FB_LOG);

    $ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           'Content-Type: application/json',
           'Content-Length: ' . strlen($data_string))
    );

    $return = curl_exec($ch);

    curl_close($ch);
    return json_decode($return, true);
  }

  public function getApiResponse(){
    return json_encode($this->apiResponse, JSON_UNESCAPED_UNICODE+JSON_PRETTY_PRINT);
  }
  /*
    метод отправки на API: messages
   */
  public function sendByMessagesMethod(){
    $this->send('messages');
  }
  /*
    метод отправки на API: messenger_profile
   */
  public function sendByMessengerProfileMethod(){
    $this->send('messenger_profile');
  }

  
  /* 
    отправляет сообщение пользователю 
  */
  public function sendMessage(){
    $this->callMessage();
    $this->sendByMessagesMethod();
  }

  /* 
  *  отправляет сообщение пользователю без ответа
  *  для рассылки
  */
  public function sendUpdateMessage(){
    $this->callUpdateMessage();
    $this->sendByMessagesMethod();
  }
  
  /* 
    активация кнопки Начать
  */
  public function getStarted(){
    $this->callGetStarted();
    $this->sendByMessengerProfileMethod();
  }

  /* 
    Активация главного меню
    Удаление меню:
    curl -X DELETE -H "Content-Type: application/json" -d '{"fields":["persistent_menu"]}' "https://graph.facebook.com/v4.0/me/messenger_profile?access_token=TOKEN" 
  */
  public function persistentMenu(){
    $this->makeButtons();
    $this->callPersistentMenu();
    $this->sendByMessengerProfileMethod();
  }

  /* 
    отправляет вложение пользователю 
  */
  public function sendAttachment(){

    if($this->params['main_menu']){
      $this->makeMainMenuElements();
    }else{  
      $this->makeElements();
    }
    
    if($this->params['more']){
      $this->makeButtonsAttachments();
    }else{
      $this->makeGenericAttachments();
    }
    
    $this->callAttachment();
    $this->sendByMessagesMethod();
  }

  /* 
    отправляет вюзеру кнопки с быстрыми ответами
  */
  public function sendQuickReply(){
    $this->makeButtons();
    $this->callQuickReply();
    $this->sendByMessagesMethod();
  }

  /* 
    отправляет кнопки юзеру 
  */
  public function sendButtons(){
    $this->makeButtons();
    $this->makeButtonAttachment();
    $this->callAttachment();
    $this->sendByMessagesMethod();
    
  }

  public function makeButtons(){
    $this->buttons = [];
    foreach ($this->buttonsContent as $key => $content) {
      $button = new Button($content);
      $this->buttons[] = $button->getReady();
    }
  }

  public function makeMainMenuElements(){
    $this->elements         = [];
    $i                      = 1;   
    
    foreach ($this->elementsContent as $key => $content) {
      $this->buttonsContent = $content['buttons']; 
      $this->makeButtons();
      $element          = new Element($content, $this->buttons);
      $this->elements[] = $element->getReady(); 
      $i++;
    }
  }

  public function makeElements(){
    $this->elements = [];
    $i              = 1;   
    
    if($this->checkNextCardParams() && $this->checkNextCardNeed()){
      $this->elementsContent[] = $this->getNextCardContent();
    }
    foreach ($this->elementsContent as $key => $content) {
      if($i == count($this->elementsContent) && $this->payloadNextCard){
        $this->nextCardComplete($content);
      }elseif(!empty($this->buttonsTemplate)){
        $this->templateButtonsComplete($content);
      }
      $this->makeButtons();
      $element          = new Element($content, $this->buttons);
      $this->elements[] = $element->getReady(); 
      $i++;
    }
  }

  /*
  *   if buttons need to complete
  *   example: unique button one slider element, with own url
  *   or next button last card slider, for more searching 
   */
  public function templateButtonsComplete($content){
    $complete     = [];
    $buttons      = [];
    // if need to complete template buttons
    $buttons             = $this->buttonsTemplate;
    $complete['more']    = Config::MORE_BUTTON_PAYLOAD_SUFFIX.$content['id'];
    $complete['url']     = $content['url'];
    $complete['payload'] = $content['id'];
    
    if(!empty($complete) && !empty($buttons)){
      $this->buttonsContent = Button::completeContent($buttons, $complete);
    }
  }

  public function nextCardComplete($content){
    $complete     = [];
    $buttons      = [];
    // if element is nextCard
    $buttons             = Config::NEXT_CARD_BUTTONS;
    $complete['next']    = $this->payloadNextCard;
    
    if(!empty($complete) && !empty($buttons)){
      $this->buttonsContent = Button::completeContent($buttons, $complete);
    }
  }

  /*
  проверяет нужна ли карточка сладера "Показать ещё"
  Если да, возвращает postback для кнопки карточки 
  */
  public function checkNextCardNeed(){
    $step             = Config::SLIDER_EVENT_STEP;        // штук для слайдера
    $next             = $this->params['slider_next'];   // текущий шаг
    $shop_id          = ($shop_id) ? $this->params['shop_id'] : '';   // текущий шаг
    $total            = $this->params['total_events'];  // кол-во элементов за весь период 
    $back             = $step * $next;            // кол-во пройденных элементов слайдера
    // если осталось ещё событий на больше 1 шага слайдера
    if($total-$back > $step) {
      $this->payloadNextCard  = Config::NEXT_CARD_PAYLOAD_SUFFIX.$shop_id.'_'.($next + 1);
      return true;
    }else{
      return false;
    }
  }

  public function checkNextCardParams(){
    $next        = $this->params['slider_next'];   
    $shop_id     = $this->params['shop_id'];   
    $total       = $this->params['total_events'];  
    return (is_int($next) && is_int($total)) ? true : false;

  }

  public function getNextCardContent(){
    return  [
      'title'         => Config::NEXT_CARD_TITLE,
      'description'   => $this->getNextCardDescription(),
      'image'         => Config::NEXT_CARD_IMAGE_URL,
    ];
  }

  public function getNextCardDescription(){
    $next        = $this->params['slider_next'];   
    $total       = $this->params['total_events'];   
    $step        = Config::SLIDER_EVENT_STEP; 
    $count       = $total - (($next+1) * $step);
    $description = Config::NEXT_CARD_DESCRIPTION_BEGIN
                    .Config::NEXT_CARD_DESCRIPTION_END
                    .$count;

    return $description;
  }



  

  
  
}

  /* c 15 августа вложенное меню запретили */
  /*public function makeMenuAction($title,$elements){
    return [
        'type'=> 'nested',
        'title'=> $title,
        'call_to_actions'=> $this->makeMenuUrls($elements)
    ];
  }*/