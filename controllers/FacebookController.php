<?php

namespace app\controllers;

use Yii; 
use yii\web\Controller;
use app\models\facebook\Config;
use app\models\facebook\Response;
use app\models\facebook\FacebookBot;
use app\models\facebook\Input;
use app\models\db\Log;
use app\models\db\Shop;
use app\models\db\Coupon;
use app\models\Now;
use app\models\Users;
//use app\models\Creditor;
/*use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;*/


class FacebookController extends Controller
{	
	//public $enableCsrfValidatiosn = false;
		
    public function actionIndex()
    {
    	if (Yii::$app->request->get('hub_verify_token') == Config::TOKEN){ 
			echo Yii::$app->request->get('hub_challenge'); 
		}
		
		$input  = json_decode(file_get_contents('php://input'));
		$now 	= new Now(new Input($input));
		//$now->sendMessage('test');
		self::saveToFile(json_encode($input, JSON_UNESCAPED_UNICODE+JSON_PRETTY_PRINT), Config::PATH_FB_LOG);
		if($now->user_id && $now->mode == 'postback' && $now->postback == 'get_started'){
			self::getStarted($now); 				// нажимает кнопку Начать
		}elseif($now->user_id && Users::findUser($now->user_id)) {
			$now->loadParams();
			if($now->params['language'] == 'en'){
				$now->replyToEnglishUsers();
				die();
			}
			switch ($now->mode) {
				case 'postback':
					self::modePostback($now);
					break;
				case 'text':
					self::modeText($now); 			
					break;
				case 'quick_reply':
					self::modePayloadQuick($now); 	
					break;
				default:
					break;
			}
		}else{
			$now->userActivate();
			switch ($now->mode) {
				case 'postback':
					self::modePostback($now);
					break;
				case 'text':
					self::modeText($now); 			
					break;
				case 'quick_reply':
					self::modePayloadQuick($now); 	
					break;
				default:
					break;
			}
		}
	}


	public function actionGo($user_id, $coupon_id){
        $coupon = Coupon::find()->where(['id' => $coupon_id])->one();
		$input = new Input([]);
        $input->user_id = $user_id;
        $session = new Now($input);
        $session->bot->user_id = $user_id;
        $session->bot->message = 'Купон: '.$coupon->promocode;
        $session->bot->sendMessage();
        Yii::$app->response->redirect($coupon->gotolink);
	}

	/* 
		пользователь нажимает кнопку Начать
	*/
	public static function getStarted($now){
		//активирует кнопку Начать
		//$now->bot->getStarted(); 	
		// активация главного меню не больше 3х
		//$now->persistentMenu(Config::MAIN_MENU_BUTTONS); 
		$now->userActivate();
	}
	/* 
		пользователь нажимает пункты меню и слайдеров
	*/
	public static function modePostback($now){
		/*if($offset = $now->checkNextPostback()){
			$now->params['offset'] = $offset;
			//$now->sendCredit();
			$now->sendFirstButtons();
		}else{
			switch ($now->postback) {
				case 'menu_about':
					//$now->sendButtons(Config::WELCOME_MESSAGE);
					break;
				default:
					break;
			}
		}*/
		if($next = $now->checkCouponNext()){
			$now->sendShopCoupons($next['shop_id'], $next['step']);
			self::saveToFile($now->bot->getApiResponse(),Config::PATH_FB_RESPONSE);
		}
		switch ($now->postback) {
				
				case 'language_ua':
					//$now->sendButtons(Config::WELCOME_MESSAGE);
					$now->params['language'] = 'ua';
					$now->saveParams();
					$now->sendProposalButtons();
					break;
				case 'language_en':
					$now->params['language'] = 'en';
					$now->saveParams();
					$now->bot->message = Config::MESSAGE_TO_ENGLISH_USERS;
					$now->bot->sendMessage();
					break;
				default:
					$now->sendMainMenu();
					break;
			}
	}

	/* 
		пользователь вводит сообщение
	*/
	public static function modeText($now){
		$text   		= trim(mb_strtolower($now->message));
		$dialog 		= [
			'привет' => 'Привет!',
			'пока'   => 'Приходи ещё!',
		];
		$cli 			= ['help', 'start', '/id'];
		if($shop = Shop::searchShop($text)){
			$now->sendMessage(Config::LABEL_SHOP.$now->message.Config::LABEL_FOUND);
			//$coupons = Coupon::getByShopId($shop->id);
			//$now->sendPartCoupons(array_slice($coupons, 0, 9));
			$now->sendShopCoupons($shop->id, 0);
			//self::saveToFile($now->bot->getApiResponse(),Config::PATH_FB_RESPONSE);
			$now->sendMainMenu();
		}elseif(array_key_exists($text, $dialog)){
			$now->sendMessage($dialog[$text]);
		}elseif(substr($text, 0, 5) == 'send ' && $now->ifAdmin()){
			$now->makeMailing(substr($now->message, 5));
		}else{
			switch ($text) {
				//case in(a, 5, 10):
				case 'help':
					$now->sendMainMenu();
					break;
				/*case 'stop':
					$now->editSubscribeStatus($status = 0);
					$now->sendUnsubscribeMessage();
					$now->sendShortMenu();
					break;
				case 'sub':
					$now->sendMessage($now->getSubscribeStatus());
					$now->sendMessage($now->subscribeLable);
					$now->sendSubscribeQuestion();
					break;
				case 'подать заявку':
					$now->formInput(str_replace(' ', '', $text));
					break;*/
				case '/id':
					//$now->sendMessage(json_encode($now->makeCitiesButtonsByShorts(['lv','dp','od']), JSON_UNESCAPED_UNICODE));
					//$now->sendInputBirthdayMessage();
					//$now->sendMessage($now->user_id.' Create:'.Users::getCreateByUserId($now->user_id));
					break;
				default:
					break;
			}
		}
	}

	/* 
		пользователь нажимает на кнопки
	*/
	public static function modePayloadQuick($now){
		
		switch ($now->postback) {
			case 'start':
				$now->sendMessage(Config::MESSAGE_START);
				break;
			case 'search':
				$now->sendMessage(Config::MESSAGE_START);
				break;
			case 'all_coupons':
				$now->sendShopCoupons(NULL, 0);
				$now->sendMainMenu();
				break;
			case 'send_yes':
				//if($now->user_id == '1965997940140423') { $now->sendMessage($result1.'\r\n'.$result2.'\r\n'.$result3.'\r\n');}
				$now->params['feedback_question'] = 'yes';
				$now->saveParams();
				$now->checkFeedbackQuestion();
				break;
			case 'send_no':
				$now->params['feedback_question'] = 'no';
				$now->saveParams();
				$now->checkFeedbackQuestion();
				break;
			case 'settings':
				$now->sendSubscribeQuestion();
				break;
			case 'subscribe':
				$now->editSubscribeStatus($status = 1);
				($now->params['order_step'] == 10) ? $now->sendShortMenu() : $now->sendProposalButtons();
				break;
			case 'unsubscribe':
				$now->editSubscribeStatus($status = 0);
				($now->params['order_step'] == 10) ? $now->sendShortMenu() : $now->sendProposalButtons();
				break;
			default:
				//$now->sendProposalButtons();
				break;
		}
	}

	public function actionFacebookDeal()
    {
    	return true;
    }

    public function actionFacebookRules()
    {
    	return true;
    }

    public static function saveToFile($log, $file) {
      //$file   = '/home/finbox/credy.com.ua/bot/log.txt';
      $stream = fopen($file, 'a') or die('can\'t open file');
      
      if ((is_array($log)) || (is_object($log))) {
          $data   = print_r($log, TRUE);
          fwrite($stream, $data."\n");
      } else {
          fwrite($stream, $log . "\n");
      }
      fclose($stream);
    }

}


/*

Комов Олег, [08.06.20 11:37]
5. Надо рассылку настроить для кредитного гавримона


 */