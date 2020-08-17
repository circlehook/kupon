<?php

namespace app\models\db;

use Yii;
use app\models\db\WrongInput;
/**
 * This is the model class for table "shop".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $url
 * @property string|null $update
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'string'],
            [['update'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'url' => 'Url',
            'update' => 'Update',
        ];
    }

    public static function searchShop($string){
        $wrong = WrongInput::find()->where(['second_name' => mb_strtolower($string)])->one();
        if($wrong){
            $find = Shop::find()->where(['id' => $wrong->shop_id])->one();
        }else{
            $find = Shop::find()->where(['title' => mb_strtolower($string)])->one();
        }
        return $find;
    }
}
