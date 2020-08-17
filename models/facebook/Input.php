<?php
namespace app\models\facebook;

class Input{

  public $user_id;
  public $message; 
  public $postback; 
  public $mode = 'text';
  
  public function __construct($input){
    if (isset($input->entry[0]->messaging[0]->sender->id)) {
      $this->user_id = $input->entry[0]->messaging[0]->sender->id; 
    }
    if (isset($input->entry[0]->messaging[0]->message->text)) {
      $this->message = $input->entry[0]->messaging[0]->message->text;
    }
    /* если нажата кнопка меню слайдера */
    if (isset($input->entry[0]->messaging[0]->postback->payload)) {
      $this->postback = $input->entry[0]->messaging[0]->postback->payload;
      $this->mode     = 'postback';
    }
    /* если нажата кнопка yes/no */
    if (isset($input->entry[0]->messaging[0]->message->quick_reply->payload)) {
      $this->postback = $input->entry[0]->messaging[0]->message->quick_reply->payload; 
      $this->mode     = 'quick_reply';
    }   
  }



}

?>