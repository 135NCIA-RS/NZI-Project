<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[UserTokens]].
 *
 * @see UserTokens
 */
class UserTokensQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UserTokens[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserTokens|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}