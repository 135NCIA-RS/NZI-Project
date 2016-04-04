<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property integer $post_id
 * @property integer $user_id
 * @property integer $owner_id
 * @property string $post_type
 * @property string $post_text
 * @property integer $post_ref
 * @property string $post_visibility
 * @property string $post_date
 * @property string $post_editdate
 * @property string $post_additionaltext
 *
 * @property Comment[] $comments
 * @property Like[] $likes
 * @property User $user
 * @property User $owner
 * @property PostAttachment[] $postAttachments
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'post_type', 'post_text', 'post_date'], 'required'],
            [['user_id', 'owner_id', 'post_ref'], 'integer'],
            [['post_date', 'post_editdate'], 'safe'],
            [['post_type', 'post_visibility'], 'string', 'max' => 20],
            [['post_text'], 'string', 'max' => 2048],
            [['post_additionaltext'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_id' => 'Post ID',
            'user_id' => 'User ID',
            'owner_id' => 'Owner ID',
            'post_type' => 'Post Type',
            'post_text' => 'Post Text',
            'post_ref' => 'Post Ref',
            'post_visibility' => 'Post Visibility',
            'post_date' => 'Post Date',
            'post_editdate' => 'Post Editdate',
            'post_additionaltext' => 'Post Additionaltext',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Like::className(), ['post_id' => 'post_id']);
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
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostAttachments()
    {
        return $this->hasMany(PostAttachment::className(), ['post_id' => 'post_id']);
    }

    /**
     * @inheritdoc
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }
}
