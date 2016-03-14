<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "score_types".
 *
 * @property integer $score_id
 * @property string $score_name
 * @property integer $score_enable
 *
 * @property Scores $scores
 */
class ScoreTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'score_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['score_name'], 'required'],
            [['score_enable'], 'integer'],
            [['score_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'score_id' => 'Score ID',
            'score_name' => 'Score Name',
            'score_enable' => 'Score Enable',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScores()
    {
        return $this->hasOne(Scores::className(), ['score_id' => 'score_id']);
    }

    /**
     * @inheritdoc
     * @return ScoreTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ScoreTypesQuery(get_called_class());
    }
}
