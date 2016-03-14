<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "score_elements".
 *
 * @property integer $elem_id
 * @property string $elem_name
 *
 * @property Scores[] $scores
 */
class ScoreElements extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'score_elements';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['elem_name'], 'required'],
            [['elem_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'elem_id' => 'Elem ID',
            'elem_name' => 'Elem Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScores()
    {
        return $this->hasMany(Scores::className(), ['element_type' => 'elem_id']);
    }

    /**
     * @inheritdoc
     * @return ScoreElementsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ScoreElementsQuery(get_called_class());
    }
}
