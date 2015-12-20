<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "relationship".
 *
 * @property integer $user1_id
 * @property integer $user2_id
 * @property string $relation_type
 *
 * @property User $user1
 * @property User $user2
 */
class Relationship extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relationship';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user1_id', 'user2_id', 'relation_type'], 'required'],
            [['user1_id', 'user2_id'], 'integer'],
            [['relation_type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user1_id' => 'User1 ID',
            'user2_id' => 'User2 ID',
            'relation_type' => 'Relation Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser1()
    {
        return $this->hasOne(User::className(), ['id' => 'user1_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser2()
    {
        return $this->hasOne(User::className(), ['id' => 'user2_id']);
    }

    /**
     * @inheritdoc
     * @return RelationshipQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RelationshipQuery(get_called_class());
    }
}
