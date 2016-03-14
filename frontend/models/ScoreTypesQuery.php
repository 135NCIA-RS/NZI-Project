<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ScoreTypes]].
 *
 * @see ScoreTypes
 */
class ScoreTypesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ScoreTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ScoreTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}