<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property integer $req_id
 * @property integer $user1_id
 * @property integer $user2_id
 * @property string $req_type
 * @property string $date
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['req_id', 'user1_id', 'user2_id', 'req_type', 'date'], 'required'],
            [['req_id', 'user1_id', 'user2_id'], 'integer'],
            [['date'], 'safe'],
            [['req_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'req_id' => 'Req ID',
            'user1_id' => 'User1 ID',
            'user2_id' => 'User2 ID',
            'req_type' => 'Req Type',
            'date' => 'Date',
        ];
    }
}
