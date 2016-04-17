<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PostAttachment]].
 *
 * @see PostAttachment
 */
class PostAttachmentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PostAttachment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PostAttachment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}