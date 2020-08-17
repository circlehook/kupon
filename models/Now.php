<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use app\models\facebook\FacebookBot;
use app\models\facebook\Config;
use app\models\facebook\Input;
use app\models\Users;
use app\models\db\Coupon;
use app\models\db\Log;
use yii\helpers\Url;
//use \GuzzleHttp\Client;

/*
* application session class.
* from receiving data to sending it
 */
class Now {

	public $user_id;
	public $params; 
  	
  	public $message; 
  	public $mode;
  	public $postback; 
	public $subscribe; 
	public $subscribeLable = ''; 
	public $categories; //flag 

  	public $bot;

	public function __construct($input){

		if($input){
			$this->user_id 	 = $input->user_id;
			$this->message 	 = $input->message;
			$this->postback  = $input->postback;
			$this->mode 	 = $input->mode;
			//$this->subscribe = $this->setSubscriptionStatus();
		}
		$this->bot 		 = new FacebookBot(Config::TOKEN, $this->user_id);
	}

	public function createUser(){
		return Users::createUser($this->user_id);
	}

	public function userActivate(){
		if($this->createUser()) { 
			$this->initParams();
			$this->sendWelcomeMessage();
			$this->sendProposalButtons();
			//$now->sendAllLettersButtons();
		}else{
			$this->sendWelcomeMessage();
			//if error save to db
			$this->sendFeedbackMessage(); die();
		}
	}

	public function initParams(){
		
		$this->params['admin'] 		 		= 0;
		$this->saveParams();
	}

	public function getParams(){
        $find_user    = Users::find()->where(['user_id' => $this->user_id])->one();
        $params       = json_decode($find_user->params,true);
		return $params;
	}

	public function getUserData(){
        $data    = Users::find()->where(['user_id' => $this->user_id])->one();
        return $data;
	}

	public function loadParams(){
		$params = $this->getParams();
        if($this->params    = $params){
    		return true;
        }
    }

    /*public function loadParams(){
		$user =  $this->getUserData();
		$params = json_decode($user->params,true);
        if($this->params = $params){
        	$this->subscribe = $user->subscribe;
    		$this->setSubscribeLabel();
    		return true;
        }
    }*/

    public function getSubscribeStatus(){
		$user = Users::find()->where(['user_id' => $this->user_id])->one();
		return $user->subscribe;
	}

    public function setSubscribeLabel(){
    	if($this->subscribe == 1){
    		$this->subscribeLable = 'Подписаны';
    	}elseif($this->subscribe == 0){
    		$this->subscribeLable = 'Отписаны';
    	}	
    }

    public function editSubscribeStatus($status){
        $user    = Users::find()->where(['user_id' => $this->user_id])->one();
        $user->subscribe = $status;
        $this->subscribe = 0;
        $this->setSubscribeLabel();
        return ($user->save()) ? true : false;
	}

	public function sendSubscribeQuestion(){
		$lable   = Config::MESSAGE_SUBSCRIBE_STATUS;
		$message = $lable.' (Вы '.$this->subscribeLable.')';
		$buttons = ($this->subscribe == 1) ? Config::TEXT_BUTTON_UNSUBSCRIBE : Config::TEXT_BUTTON_SUBSCRIBE;
		$this->bot->message 		= $message;
		$this->bot->buttonsContent  = $buttons;
		$this->bot->sendQuickReply();
	}

	public function sendUnsubscribeMessage(){
		$lable   = Config::MESSAGE_SUBSCRIBE_STATUS;
		$message = $lable.' (Вы '.$this->subscribeLable.')';
		$this->bot->message 		= $message;
		$this->bot->sendMessage();
	}

	public function saveParams(){
	    $find    = Users::find()->where(['user_id' => $this->user_id])->one();
	    $find->params = json_encode($this->params, JSON_UNESCAPED_UNICODE+JSON_PRETTY_PRINT);
	    return ($find->save()) ? true : false;

	}

	public function checkCyrillic($text){
		if (preg_match("/[а-я]+/i", $text)) {
	    	return true;
	    }
	}

	public function checkDate($text){
		if (preg_match("/(\d{1,2})\.(\d{1,2})\.(\d{4})/i", $text)) {
	    	return true;
	    }
	}

	public function checkPhone($text){
		if (preg_match("/^0\d{3}\d{2}\d{2}\d{2}$/i", $text)) {
	    	return true;
	    }
	}

	public function checkInn($text){
		if (preg_match("/^\d{10}$/i", $text)) {
	    	return true;
	    }
	}

	public function checkSumma($text){
		if (preg_match("/[0-9]+/i", $text)) {
			return true;
		}
	}

	public function checkPassport($text){
		if (preg_match("/[А-ГҐДЕЄЖЗИІЇЙК-ЯABCEHIKMOPTX]{2}\d{6}$/", $text)){
			return true;
		}
	}

	public function checkEmail($text){
		return (filter_var($text, FILTER_VALIDATE_EMAIL)) ? true : false;
	}

	public function sendWelcomeMessage(){
		$this->bot->message = Config::MESSAGE_WELCOME;
		$this->bot->sendMessage();
	}

	public function sendFeedbackMessage(){
		$this->bot->message = Config::MESSAGE_FEEDBACK;
		$this->bot->sendMessage();
	}

	public function sendZeroEventsMessage(){
		$this->bot->message = Config::MESSAGE_ZERO_FOUND;
		$this->bot->sendMessage();
	}

	public function sendMessage($message){
		$this->bot->message = $message;
		$this->bot->sendMessage();
	}

	public function sendProposalButtons(){
		$this->bot->message 		= Config::MESSAGE_PROPOSAL;
		$this->bot->buttonsContent 	= Config::START_BUTTONS;
		$this->bot->sendQuickReply();
	}

	public function sendShortMenu(){
		$this->bot->message 		= Config::MESSAGE_PROPOSAL;
		$this->bot->buttonsContent 	= Config::SHORT_MENU_BUTTONS;
		$this->bot->sendQuickReply();
	}

	public function sendMainMenu(){
		$this->bot->message 		= Config::MESSAGE_MAIN_MENU;
		$this->bot->buttonsContent 	= Config::MAIN_BUTTONS;
		$this->bot->sendQuickReply();
	}

	public function sendQuestionButtons(){
		$this->bot->message 		= Config::MESSAGE_INPUT_QUESTION;
		$this->bot->buttonsContent 	= Config::SEND_ORDER_BUTTONS;
		$this->bot->sendQuickReply();
	}

	public function sendMailingButtons(){
		$this->bot->message 		= Config::MESSAGE_MAILING_QUESTION;
		$this->bot->buttonsContent 	= Config::MAILING_BUTTONS;
		$this->bot->sendQuickReply();
	}

	public function sendEmploymentButtons(){
		$this->bot->message 		= Config::MESSAGE_INPUT_EMPLOYMENT;
		$this->bot->buttonsContent 	= Config::EMPLOYMENT_BUTTONS;
		$this->bot->sendQuickReply();
	}

	public function sendOnlineCredits(){
		$this->sendOffers(Config::ONLINE_CREDITS);
	}

	public function checkCouponNext(){
	    $result  = []; 
	    if(preg_match('/next_(.*?)/U', $this->postback, $find)){
	      $next    = explode('_', $find[1]);
	      $result['shop_id']  = (int)$next[0];
	      $result['step']     = (int)$next[1];
	      return $result; 
	    }else{
	      return false; 
	    }
  	}

  	public function sendShopCoupons($shop_id, $step = 0){
		$this->makeAtachmentParams($shop_id, $step);
		$coupons = Coupon::getSome($shop_id, $step);
  		//Log::saveToFile(json_encode($events), Config::PATH_FB_LOG);
		if(count($coupons) > 0){
			$this->sendPartCoupons($coupons);
		}else{
			$this->sendZeroEventsMessage();
		}
	}

	public function sendPartCoupons($coupons){
		$coupons = $this->modifyOffersContent($coupons);
		$this->bot->elementsContent = $coupons;
		$this->bot->buttonsTemplate = Config::TEMPLATE_BUTTONS;
		$this->bot->sendAttachment(); 
	}

	public function makeAtachmentParams($shop_id, $step){
	    $total  = Coupon::getCount($shop_id); 
	    $this->bot->params['slider_next'] 	= $step; 
	    $this->bot->params['shop_id'] 		= $shop_id; 
	    $this->bot->params['total_events']  = $total; 

	    $this->params['slider_next'] 		= $step; 
	    $this->params['shop_id'] 			= $shop_id; 
	    $this->params['total_events']   	= $total; 
	    
	}

	/*
	*	modify only image field. 
	*	key from array = image filename without .png
	 */
	public function modifyOffersContent($items){
		foreach ($items as $key => $item) {
			$item['title']       = $item['name'].'('.$item['discount'].')';
			$item['description'] = (is_null($item['description'])) ? $item['discount'] : $item['description'];
			$url 				 = Url::to(['facebook/go', 'user_id' => $this->user_id, 'coupon_id' => $item['id']], true);
		    $item['url'] 		 = $url;
		    $item['image']   	 = $item['logo'];

		    $result[] = $item;
		}
		return $result;	
	}

	public function setStartOfInput(){
		$this->params['start_input'] = 1; 
		$this->params['order_step']  = 1; 
		$this->saveParams();
		$user = Users::find()->where(['user_id' => $this->user_id])->one();
		$user->start = 1;
		$user->save();
	}

	public function checkWhichStep(){
		return ($this->params['order_step'] <= 8) ? $this->params['order_step'] : false;
	}


	public function formInput($text){
		$step = $this->checkWhichStep();
		switch ($step) {
			case 0:
				//$this->sendProposalButtons();
				$this->setStartOfInput();
				break;
			case 1:
				$this->stepPhone($text);
				break;
			case 2:
				$this->stepName($text);
				break;
			default:
				break;
		}
	}
	

	/*
	* step 1
	 */
	public function stepPhone($text){
		if($this->checkPhone($text)){
			$this->params['phone'] = $text;
			$this->params['order_step']++;
			$this->saveParams();
			$user = Users::find()->where(['user_id' => $this->user_id])->one();
        	$user->time_phone = date("Y-m-d H:i:s");
        	$user->save();
			Creditor::sendAfterPhone($this);
			$this->sendInputBackPhoneMessage();
			$this->sendInputNameMessage();
		}else{
			$this->sendInputPhoneMessage();								
		}
	}


	/*
	* step 2 
	 */
	public function stepName($text){
		if($this->checkCyrillic($text)){
			$this->params['first_name'] = $text;
			$this->params['order_step']++;
			$this->saveParams();
			$this->sendInputLastNameMessage();
		}else{
			$this->sendInputNameMessage();								
		}
	}

	public static function validateForm($params){

		$error_count = 0;
        
        //(empty($params['first_name']))   ? $error_count++ : true;
        //(empty($params['last_name']))    ? $error_count++ : true;
        //(empty($params['parent_name']))  ? $error_count++ : true;
        (empty($params['phone']))        ? $error_count++ : true;
        //(empty($params['birth_date']))   ? $error_count++ : true;
        //(empty($params['employment']))   ? $error_count++ : true;
        //(empty($params['region']))	     ? $error_count++ : true;
        if($error_count == 0){
            
            //$user_fields = Now::buildAdmitApiArray($this->params, $campaign_id);                        
            //$result 	 =  Now::sendDataToAdmit($user_fields, Now::getAdmitToken());
        	//$this->sendMessage(json_encode($result));
			//Logs::staticSave($this->user_id, 'send_order', (string)$campaign_id, json_encode($result));
            return true;
        } 
	}

	

	

	public function ifAdmin(){
		return ($this->params['admin'] == 1) ? true : false;
	}

	/*
	* multi send message to all db users by user_id field
	 */
	public function multiSend($message){
		$result = Users::getIds();
        foreach ($result as $key => $user) {
	        if(!is_null($user['user_id']) && !empty($user['user_id'])){
	        	$input = new Input([]);
                $input->user_id = $user['user_id'];
                $session = new Now($input);
                $session->bot->user_id = $user['user_id'];
                $session->bot->message = $message;
                $session->bot->sendUpdateMessage();
	            $find = Users::find()->where(['user_id' => $user['user_id']])->limit(1)->one();
	            if($find){
	            	$find->send = 1;
	            	$find->save();
	            }
	            unset($user);	
	            unset($input);	
	            unset($session);	
	        }
        }
        unset($result);
	}

	public function makeMailing($message){
		$option = new Option();
		$option->option  = 'mass_mailing';
		$option->value = json_encode($message);
		$option->save();
	}

}


?>