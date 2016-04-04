<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "photo".
 *
 * @property integer $user_id
 * @property string $filename
 * @property string $type
 *
 * @property User $user
 */
class Photo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'filename', 'type'], 'required'],
            [['user_id'], 'integer'],
            [['filename'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'type' => 'Type',
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
     * @return PhotoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PhotoQuery(get_called_class());
    }

    public static function primaryKey()
    {
        //parent::primaryKey();
        return ["user_id","filename","type"];
    }
}
