<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ScoreElements]].
 *
 * @see ScoreElements
 */
class ScoreElementsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ScoreElements[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ScoreElements|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}