<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scores".
 *
 * @property integer $score_id
 * @property integer $user_id
 * @property integer $element_id
 * @property integer $element_type
 *
 * @property ScoreTypes $score
 * @property User $user
 * @property ScoreElements $elementType
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
            [['user_id', 'element_id', 'element_type'], 'required'],
            [['user_id', 'element_id', 'element_type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'score_id' => 'Score ID',
            'user_id' => 'User ID',
            'element_id' => 'Element ID',
            'element_type' => 'Element Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScore()
    {
        return $this->hasOne(ScoreTypes::className(), ['score_id' => 'score_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementType()
    {
        return $this->hasOne(ScoreElements::className(), ['elem_id' => 'element_type']);
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
