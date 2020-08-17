<?php

namespace app\models\facebook;

class Config{

	const TOKEN  = 'EAALRRIkYiiIBADkri8yjmMj0zXxsHZAeyZC9JxYgnviacdyqsJQFdA4ZAwMsUZBF4jZBvviQbUrppZAxC01xvxwatZCF8pHyMmUh7R80G64HQgdDecXjL4hKTqNWxqXsM2SuInZCzvex75XUGsKM4xdXpQ1agR34T5yhgGlC3NPNGgZDZD';
	const PATH_FB_LOG 			 	= '/home/finbox/credy.com.ua/bots/kupon/controllers/input.log';
	const PATH_FB_RESPONSE 			= '/home/finbox/credy.com.ua/bots/kupon/controllers/response.log';
	const PATH_TO_IMAGES 			= 'https://bots.credy.com.ua/moneybot3/web/images/';
	//const  FACEBOOK_PAGE_URL    = 'https://www.facebook.com/Toro24-101096131270494/';
	//const  IMAGE_FILE_EXTENSION = 'png';
	const MORE_BUTTON_PAYLOAD_SUFFIX 	= 'more-';
	const SLIDER_EVENT_STEP	 			= 9;
	const NEXT_CARD_TITLE       		= 'Хочете ще купонiв?';
	const NEXT_CARD_DESCRIPTION_BEGIN 	= 'У магазинi ';
	const NEXT_CARD_DESCRIPTION_END   	= ' доступно ще: ';
	const NEXT_CARD_BUTTON_TITLE 		= 'Показати ще..';
	const NEXT_CARD_IMAGE_URL  			= 'https://karabas.vutkabot.com.ua/frontend/web/images/search.png';
	const NEXT_CARD_PAYLOAD_SUFFIX   	= 'next_';

	const SUFFIX_REGION 		= 'region_';
    const SUFFIX_REGIONS	 	= 'regions_';
    const SUFFIX_EMPLOYMENT 	= 'employment_';
    const NUMBER_LETTERS  		= 3;
	const  FREE_BUTTON_TITLE 	= 'Проверьте бесплатно';
	const  URL_BUTTON_TITLE     = 'Получить деньги';
	const  MORE_BUTTON_TITLE    = 'Ещё варианты';
	const  BOT_DESCRIPTION      = 'Этот бот делает ништяки!';
	const  ZERO_FOUND_MESSAGE   = 'Кредитов не найдено.';
	const  TAKE_MESSAGE    		= 'Воспользуйтесь кнопками ниже:';
	const  TAKE_SUMMA_MESSAGE   = 'Выбери сумму займа';
	const  TAKE_PERIOD_MESSAGE   = 'Выбери срок займа';
	const  TAKE_PERCENT_MESSAGE   = 'Выбери % займа';

	const LABEL_SHOP = 'Магазин ';
	const LABEL_FOUND = ' знайден';

	
	const TITLE_URL_BUTTON      		= 'Вiдкрити код';
	const MESSAGE_ZERO_FOUND    		= 'Событий не найдено.';
	const MESSAGE_WELCOME   			= "Привiт 🏻 \r\n
У нашому боті ви зможете знайти всі актуальні та секретні промокоди на великих популярних магазинах і сервісах.\r\n
Наша команда щодня піклується про актуальність і працездатності всіх промокодiв та скідок🤑
";
	const MESSAGE_START = "Для отримання інформації про магазин набери в чаті точну назву магазину, наприклад: «Телемарт»
Бот завжди активний для пошуку, достатньо в будь-якому чаті набрати «НазваМагазину».
";
	const MESSAGE_FEEDBACK     			= 'Настройки не cохранены. Обратитесь к администратору ';
	const MESSAGE_PROPOSAL     			= 'Будь ласка, виберіть і натисніть кнопку';
	const MESSAGE_MAIN_MENU     		= 'Выберите действие';
	
	const MESSAGE_INPUT_QUESTION		= "Заявка безкоштовна та її подача Вас нічим не зобов'язує. Ви згодні, щоб з Вами зв'язався співробітник Банку для уточнення деталей?";
	const MESSAGE_INPUT_DONE			= 'Ваші заявки подані успішно. Будь ласка, чекайте комунікацій з боку банків протягом 24 годин. Дякуємо.';
	const MESSAGE_ALREADY_DONE			= 'Вы уже ранее подавали заявку. Если Вы хотите подать заявку от имени другого человек, то передайте ему ссылку на чат-бот https://m.me/gavrimon и он сможет подать заявку . Для Вас доступны следующие предложения';

	const MESSAGE_SUBSCRIBE_STATUS = 'Подписка';

	const TEXT_BUTTON_SUBSCRIBE			= [ 0 => ['title' => 'Подписаться',   	'type' => 'text', 'payload' => 'subscribe' ]];
	const TEXT_BUTTON_UNSUBSCRIBE		= [ 0 => ['title' => 'Отписаться',    	'type' => 'text', 'payload' => 'unsubscribe' ]];

	const URL_BUTTON_HOUR_SEND = [ 
		0 => [ 'title' => 'Перейти', 'type'  => 'web_url',  'url'   => 'https://fas.st/U2L_x' ]
	];
	
	const TEXT_BUTTON_START			= ['title' => 'Давайте почнемо', 	'type' => 'text', 'payload' => 'start' ];
	const  START_BUTTONS    = [
		0 => self::TEXT_BUTTON_START,
		
	];

	const TEMPLATE_BUTTON_NEXT 			= ['title' => Config::NEXT_CARD_BUTTON_TITLE, 	'type' => 'postback', 'option' => 'next'];

	const  NEXT_CARD_BUTTONS    = [
		0 => self::TEMPLATE_BUTTON_NEXT,
		
	];

	const TEXT_BUTTON_SEARCH		= ['title' => 'Пошук',  	'type' => 'text', 'payload' => 'search'];
	const TEXT_BUTTON_PROMOCODES	= ['title' => 'Всi промокоди', 	'type' => 'text', 'payload' => 'all_coupons' ];
	
	const  MAIN_BUTTONS    = [
		0 => self::TEXT_BUTTON_SEARCH,
		1 => self::TEXT_BUTTON_PROMOCODES,
	];

	const  SHORT_MENU_BUTTONS    = [
		0 => self::TEXT_BUTTON_CREDITS_ON_CARD,
		1 => self::TEXT_BUTTON_ONLINE_CREDITS,
		2 => self::TEXT_BUTTON_KURS,
		3 => self::TEXT_BUTTON_SETTINGS,
	];

	const TEMPLATE_BUTTON_URL  = ['title' => Config::TITLE_URL_BUTTON, 'type' => 'web_url'];

	const  TEMPLATE_BUTTONS    = [
		0 => self::TEMPLATE_BUTTON_URL,
	];

	const TEXT_BUTTON_SEND_ORDER_YES		= ['title' => 'Так',  	'type' => 'text', 'payload' => 'send_yes'];
	const TEXT_BUTTON_SEND_ORDER_NO			= ['title' => 'Нi',  	'type' => 'text', 'payload' => 'send_no'];

	const  SEND_ORDER_BUTTONS    = [
		0 => self::TEXT_BUTTON_SEND_ORDER_YES,
		1 => self::TEXT_BUTTON_SEND_ORDER_NO,
	];

	
	const TEXT_BUTTON_MAILING_YES		= ['title' => 'Да',  	'type' => 'text', 'payload' => 'mailing_yes'];
	const TEXT_BUTTON_MAILING_NO		= ['title' => 'Нет',  	'type' => 'text', 'payload' => 'mailing_no'];

	const  MAILING_BUTTONS    = [
		0 => self::TEXT_BUTTON_MAILING_YES,
		1 => self::TEXT_BUTTON_MAILING_NO,
	];

	const TEXT_BUTTON_MONEYVEO_YES = ['title' => 'Да',      'type' => 'text', 'payload' => 'take' ];
    const TEXT_BUTTON_MONEYVEO_NO = ['title' => 'Нет',      'type' => 'text', 'payload' => 'not_take' ];

    const  MONEYVEO_QUESTION_BUTTONS    = [
		0 => self::TEXT_BUTTON_MONEYVEO_YES,
		1 => self::TEXT_BUTTON_MONEYVEO_NO,
	];

	const URL_BUTTON_MONEYVEO_CARD = [ 'title' => 'Подать заявку', 'type'  => 'web_url',  'url'   => 'https://fingo.com.ua/moneyveo/' ];
	const  MONEYVEO_CARD_BUTTONS    = [
		0 => self::URL_BUTTON_MONEYVEO_CARD,
	];

	

	

	const CREDITS_ON_CARD = [
		'moneyveo1' 			=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Moneyveo'			,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'ccloan1' 				=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Ccloan'			,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'sgroshi1' 				=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Швидко грошi'		,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'kreditap' 				=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'CreditUp'			,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'credit365-min-1' 		=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Credit365'		,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'credit_card_alfabank' 	=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Alfa банк'		,'description' => 'Первый кредит 0%50000,срок 1год'],
		'bistrozaim' 			=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Быстро займ'		,'description' => 'Первый кредит 0%,9000,срок 1год'],
		'Globalcredit' 			=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Global Credit'	,'description' => 'Первый кредит 0%,11000,срок 1год'],
		'soscredit' 			=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'SOS credit'		,'description' => 'Без справок,15000,срок 1год'],
		'kfua1' 				=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Kf'				,'description' => 'Первый кредит 0%,15000,срок 1год'],
	];

	const ONLINE_CREDITS = [
		'cashinsky1' 			=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Cashinsky'		,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'moneyboom' 			=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Money-boom'		,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'klt' 					=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Kltcredit'		,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'ultracash1' 			=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Ultra-cash'		,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'prostozaim1' 			=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Prosto-zaym'		,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'e-kash' 				=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'E-Cash'			,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'AlexCredit' 			=> ['url'=>'http://groshi-v-borg.com.ua/' 	, 'title' => 'Alexcredit'		,'description' => 'Первый кредит 0%,15000,срок 1год'],
		'miloan1' 				=> ['url'=>'http://groshi-v-borg.com.ua/'	, 'title' => 'Miloan'			,'description' => 'Первый кредит 0%,15000,срок 1год'],
		
	];




	/* кнопки основного меню */
	/*const MAIN_MENU_BUTTONS = [
		'Выбрать кредиты' => [
			0 => ['title' => 'Кредитная карта' , 'type' => 'postback', 'payload' => 'menu_one_cards'  ],
			1 => ['title' => 'Кредиты на карту', 'type' => 'postback', 'payload' => 'menu_one_credits'],
			2 => ['title' => 'Онлайн кредит'   , 'type' => 'postback', 'payload' => 'menu_one_online' ]
		],
		'Дополнительно' => [
			0 => ['title' => 'Подобрать кредит', 'type' => 'postback', 'payload' => 'menu_two_select' ],
			1 => ['title' => 'Оставить отзыв'  , 'type' => 'web_url',  'url'     => self::FACEBOOK_PAGE_URL ],
			//2 => ['title' => 'Поделиться ботом', 'type' => 'web_url',  'url' 	 => self::FACEBOOK_PAGE_URL ]
		]
	];*/

	/* первые кнопки */
	/*const  FIRST_BUTTONS    = [
		0 => ['title' => 'Подбор займа',  'type' => 'text', 'payload' => 'find'],
		1 => ['title' => 'Популярные предложения',  'type' => 'text', 'payload' => 'popular' ]
	];*/

	/* кнопки выбора суммы*/
	/*const  SUMMA_BUTTONS    = [
		0 => ['title' => 'до 5000 грн'   	 , 'type' => 'text', 'payload' => '0_5000'  ],
		1 => ['title' => 'от 5000 до 10000'  , 'type' => 'text', 'payload' => '5000_10000'  ],
		2 => ['title' => 'от 10000 до 20000' , 'type' => 'text', 'payload' => '10000_20000' ],
		3 => ['title' => 'более 20000'       , 'type' => 'text', 'payload' => '20000_999999' ]
	];
	const  SUMMA_PAYLOADS 	= ['0_5000','5000_10000','10000_20000','20000_999999'];*/

	/*const  PERIOD_BUTTONS    = [
		0 => ['title' => 'До 20 дней'   	 , 'type' => 'text', 'payload' => '0_20'  ],
		1 => ['title' => 'От 21 до 30 дней'  , 'type' => 'text', 'payload' => '21_30'  ],
		2 => ['title' => 'Свыше 30 дней' 	 , 'type' => 'text', 'payload' => '31_365' ]
	];

	const  PERIOD_PAYLOADS 	= ['0_20','21_30','31_365'];

	const  PERCENT_BUTTONS    = [
		0 => ['title' => 'Только 0%'   	 		  , 'type' => 'text', 'payload' => 'zero_percent'  ],
		1 => ['title' => 'Покажите все варианты'  , 'type' => 'text', 'payload' => 'all_percent'  ]
	];*/






	
	/*
	главное меню
	 */

	/*const BESTSELLERS = [
		0 => ['title' => 'Szybka Gotowka 0%', 'url' => 'http://fas.st/_oCVaP'],
		1 => ['title' => 'Fastero 0%', 'url' 		=> 'http://fas.st/Vt_M-F'],
		2 => ['title' => 'Solcredit 0%', 'url' 		=> 'http://fas.st/UrJOU'],

	];*/


}


?>