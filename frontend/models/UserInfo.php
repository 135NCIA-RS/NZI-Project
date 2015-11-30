<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "userInfo".
 *
 * @property integer $user_id
 * @property string $user_name
 * @property string $user_surname
 * @property string $user_birthdate
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userInfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_name', 'user_surname', 'user_birthdate'], 'required'],
            [['user_id'], 'integer'],
            [['user_name', 'user_surname'], 'string', 'max' => 255],
            [['user_birthdate'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_surname' => 'User Surname',
            'user_birthdate' => 'User Birthdate',
        ];
    }

    /**
     * @inheritdoc
     * @return UserInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserInfoQuery(get_called_class());
    }
}
