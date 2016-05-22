<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "userTokens".
 *
 * @property integer $token_id
 * @property integer $user_id
 * @property string $token
 * @property integer $token_type
 * @property integer $token_expirable
 * @property string $token_expiration_date
 *
 * @property User $user
 */
class UserTokens extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userTokens';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'token_type'], 'required'],
            [['user_id', 'token_type', 'token_expirable'], 'integer'],
            [['token_expiration_date'], 'safe'],
            [['token'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token_id' => 'Token ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'token_type' => 'Token Type',
            'token_expirable' => 'Token Expirable',
            'token_expiration_date' => 'Token Expiration Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return UserTokensQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserTokensQuery(get_called_class());
    }
}
