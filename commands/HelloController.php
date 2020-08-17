<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;
use app\models\facebook\Input;
use app\models\facebook\FacebookBot;
use app\models\facebook\Response;
use app\models\Now;
use app\models\db\Coupon;
use app\models\db\Shop;
use GuzzleHttp\Client;
use yii\helpers\Url;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionGet(){
        //$url = 'http://export.admitad.com/ua/webmaster/websites/342455/coupons/export/?website=342455&advcampaigns=20130&region=00&code=9a0bfe74cb&user=klientovod&format=xml&v=1';
        $shop = Shop::find()
            ->where(['<', 'update', new Expression('NOW() - INTERVAL 1 DAY')])
            ->orWhere(['IS', 'update', NULL])
            ->one();
        (!$shop) ? exit() : true;

        $client     = new Client([]);
        $response   = $client->get($shop->url);
        $body       = $response->getBody();
        $result     = $body->getContents();
        $xml        = simplexml_load_string($result);
        $coupons    = $xml->coupons->coupon;
        $categories = $xml->categories->category;
        
        foreach ($categories as $category) {
            $id       = (string)$category['id'];
            $cats[$id] = (string)$category;
        }
        Coupon::deleteAll(['shop_id' => $shop->id]);
        foreach ($coupons as $key => $coupon) {
            $new_coupon = new Coupon();
            $new_coupon->shop_id     = $shop->id;
            $new_coupon->coupon_id   = $coupon['id'];
            $new_coupon->category    = $cats[(string)$coupon->categories->category_id];
            $new_coupon->name        = (string)$coupon->name;
            $new_coupon->description = str_replace(array("\r\n", "\r", "\n"), '', $coupon->description);
            $new_coupon->logo        = (string)$coupon->logo;
            $new_coupon->promocode   = (string)$coupon->promocode;
            $new_coupon->promolink   = (string)$coupon->promolink;
            $new_coupon->gotolink    = (string)$coupon->gotolink;
            $new_coupon->date_start  = $coupon->date_start;
            $new_coupon->date_end    = $coupon->date_end;
            //date("Y-m-d H:i:s")
            $new_coupon->discount    = (string)$coupon->discount;
            echo ($new_coupon->save()) ? 'saved' : 'not saved';
            echo "\r\n";
            var_dump($new_coupon->errors);
        }
        $shop->update = date("Y-m-d H:i:s");
        $shop->save();
    }

    public function actionTest(){
        echo Url::to(['facebook/go', 'user_id' => 100, 'coupon_id' => 99]);
        //var_dump($find); 
    } 

    public function actionSend(){
        $input = new Input([]);
        $input->user_id = '2953614638082813';
        $session = new Now($input);
        $session->bot->user_id = '2953614638082813';
        //$session->bot->message = $message;
        $coupons = Coupon::getByShopId($shop->id);
        $session->sendCoupons($coupons);
            //$now->sendCoupons(array_slice($coupons, 0, 9));
        echo $session->bot->getApiResponse();
    }

    public function actionAll(){
        var_dump(Coupon::getAll());
    }
}
