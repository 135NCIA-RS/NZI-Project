<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Event".
 *
 * @property integer $event_id
 * @property integer $event_type
 * @property integer $event_owner
 * @property integer $event_user_connected
 * @property string $date
 * @property string $event_data_connected
 *
 * @property User $eventOwner
 * @property User $eventUserConnected
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_type', 'event_owner'], 'required'],
            [['event_type', 'event_owner', 'event_user_connected'], 'integer'],
            [['date'], 'safe'],
            [['event_data_connected'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_id' => 'Event ID',
            'event_type' => 'Event Type',
            'event_owner' => 'Event Owner',
            'event_user_connected' => 'Event User Connected',
            'date' => 'Date',
            'event_data_connected' => 'Event Data Connected',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'event_owner']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventUserConnected()
    {
        return $this->hasOne(User::className(), ['id' => 'event_user_connected']);
    }

    /**
     * @inheritdoc
     * @return EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventQuery(get_called_class());
    }
}
