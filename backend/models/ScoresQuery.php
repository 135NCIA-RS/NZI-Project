<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Scores]].
 *
 * @see Scores
 */
class ScoresQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Scores[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Scores|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}