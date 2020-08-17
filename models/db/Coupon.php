<?php

namespace app\models\db;

use Yii;
use app\models\db\Shop;
use app\models\facebook\Config;

/**
 * This is the model class for table "coupon".
 *
 * @property int $id
 * @property int|null $shop_id
 * @property string|null $category
 * @property string|null $name
 * @property int|null $coupon_id
 * @property string|null $logo
 * @property string|null $promocode
 * @property string|null $promolink
 * @property string|null $gotolink
 * @property string|null $date_start
 * @property string|null $date_end
 * @property string|null $discount
 * @property string $create
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'coupon_id'], 'integer'],
            [['logo', 'promolink', 'gotolink', 'description'], 'string'],
            [['date_start', 'date_end', 'create'], 'safe'],
            [['category', 'name', 'promocode', 'discount'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'category' => 'Category',
            'name' => 'Name',
            'coupon_id' => 'Coupon ID',
            'logo' => 'Logo',
            'promocode' => 'Promocode',
            'promolink' => 'Promolink',
            'gotolink' => 'Gotolink',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'discount' => 'Discount',
            'create' => 'Create',
        ];
    }

    public static function getByShopId($shop_id){
        $find = Coupon::find()
            ->where(['shop_id' =>$shop_id])
            ->asArray()
            ->all();
        return $find;
    }

    public static function getAll(){
        $find = Coupon::find()
            ->asArray()
            ->all();
        return $find;
    }

    public static function getCountByShopId($shop_id){
        return count(self::getByShopId($shop_id));
    }

    public static function getCount($shop_id){
        $all = ($shop_id) ? self::getByShopId($shop_id) : self::getAll();
        return count($all);
    }

    public function getParentName(){
        $name = (new \yii\db\Query())
            ->select(['title'])
            ->from('shop')
            ->where(['id' => $this->shop_id])
            ->one();
        return $name['title'];
    }

    public static function getSome($shop_id, $step = 0){
        $all = ($shop_id) ? self::getByShopId($shop_id) : self::getAll();
        $step       = $step * Config::SLIDER_EVENT_STEP;
        $coupons    = array_slice($all, $step, Config::SLIDER_EVENT_STEP);
        return $coupons;
    }

}
