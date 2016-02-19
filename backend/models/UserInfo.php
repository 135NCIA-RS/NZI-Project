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
 * @property string $user_education
 * @property string $user_city
 * @property string $user_about
 *
 * @property User $user
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
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['user_name', 'user_surname'], 'string', 'max' => 255],
            [['user_birthdate'], 'string', 'max' => 10],
            [['user_education', 'user_city'], 'string', 'max' => 256],
            [['user_about'], 'string', 'max' => 1024]
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
            'user_education' => 'User Education',
            'user_city' => 'User City',
            'user_about' => 'User About',
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
     * @return UserInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserInfoQuery(get_called_class());
    }
}
