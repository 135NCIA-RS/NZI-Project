<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scores".
 *
 * @property integer $score_id
 * @property integer $score_type
 * @property integer $user_id
 * @property integer $element_id
 * @property integer $element_type
 *
 * @property User $user
 */
class Scores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['score_type', 'user_id', 'element_id', 'element_type'], 'required'],
            [['score_type', 'user_id', 'element_id', 'element_type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'score_id' => 'Score ID',
            'score_type' => 'Score Type',
            'user_id' => 'User ID',
            'element_id' => 'Element ID',
            'element_type' => 'Element Type',
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
     * @return ScoresQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ScoresQuery(get_called_class());
    }
}
