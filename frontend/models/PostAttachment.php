<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_attachment".
 *
 * @property integer $attachment_id
 * @property integer $post_id
 * @property string $file
 *
 * @property Post $post
 */
class PostAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'file'], 'required'],
            [['post_id'], 'integer'],
            [['file'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attachment_id' => 'Attachment ID',
            'post_id' => 'Post ID',
            'file' => 'File',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['post_id' => 'post_id']);
    }

    /**
     * @inheritdoc
     * @return PostAttachmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostAttachmentQuery(get_called_class());
    }
}
