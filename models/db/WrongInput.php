<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "wrong_input".
 *
 * @property int $id
 * @property int $shop_id
 * @property string|null $second_name
 */
class WrongInput extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wrong_input';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id'], 'required'],
            [['shop_id'], 'integer'],
            [['second_name'], 'string', 'max' => 255],
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
            'second_name' => 'Second Name',
        ];
    }

    public function getParentName(){
        $name = (new \yii\db\Query())
            ->select(['title'])
            ->from('shop')
            ->where(['id' => $this->shop_id])
            ->one();
        return $name['title'];
    }
}
