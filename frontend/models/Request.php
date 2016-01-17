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
 *
 * @property User $user2
 * @property User $user1
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
            [['user1_id', 'user2_id', 'req_type', 'date'], 'required'],
            [['user1_id', 'user2_id'], 'integer'],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser2()
    {
        return $this->hasOne(User::className(), ['id' => 'user2_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser1()
    {
        return $this->hasOne(User::className(), ['id' => 'user1_id']);
    }

    /**
     * @inheritdoc
     * @return RequestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RequestQuery(get_called_class());
    }
}
