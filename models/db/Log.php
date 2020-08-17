<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property string $type
 * @property string $event
 * @property string $target
 * @property string $result
 * @property string $create
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'event', 'target', 'result'], 'required'],
            [['result'], 'string'],
            [['create'], 'safe'],
            [['type', 'event', 'target'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'event' => 'Event',
            'target' => 'Target',
            'result' => 'Result',
            'create' => 'Create',
        ];
    }
}
