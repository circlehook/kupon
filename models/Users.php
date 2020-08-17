<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $language_code
 * @property string|null $params
 * @property string $create
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['params'], 'string'],
            [['create'], 'safe'],
            [['user_id'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['language_code'], 'string', 'max' => 10],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'language_code' => 'Language Code',
            'params' => 'Params',
            'create' => 'Create',
        ];
    }

    public static function createUser($user_id){
        if($user = Users::findUser($user_id)){
            //$user->last_time = date("Y-m-d H:i:s");
            $user->save();    
            return true;
        }else{
            $new = new Users();
            $new->user_id = $user_id;
            if($new->save()){
                //$id = Yii::$app->db->getLastInsertID();
                return true;
            }else{
                return false;
            }
        }
    }

    public static function findUser($user_id){
        $find = Users::find()->where(['user_id' => $user_id])->one();
        return ($find) ? $find : false;
    }
}
