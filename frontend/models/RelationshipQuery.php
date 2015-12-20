<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Relationship]].
 *
 * @see Relationship
 */
class RelationshipQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Relationship[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Relationship|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}