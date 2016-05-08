<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 5/8/2016
 * Time: 12:20 PM
 */

namespace common\components;


use app\models\Event;

class EventService
{
	public static function createEvent(EEvent $event, UserId $event_owner, $self = true, UserId $user_connected = null, $data_connected = null)
	{
		$e = new Event();
		$e->event_owner = $event_owner->getId();
		$e->event_type = $event->getValue();
		if(!is_null($user_connected))
		{
			$e->event_user_connected = $user_connected->getId();
		}
		$data_c = [];
		$data_c['self_mode'] = $self;
		$e->event_data_connected = json_encode($data_c);
		$date = new \DateTime();
		$e->date = $date->format("Y-m-d H:i:s");
		$e->save();
	}

	public static function getUserEvents(UserId $uid, $sort = true)
	{
		if($sort)
		{
			$e = Event::find()->where(['event_owner' => $uid->getId()])->orderBy(['date' =>SORT_DESC])->all();
		}
		else
		{
			$e = Event::findAll(['event_owner' => $uid->getId()]);
		}
		$events = [];
		foreach($e as $event)
		{
			$type = EEvent::search($event->event_type);
			$uconnected = ($event->event_user_connected == null) ? null : new UserId($event->event_user_connected);
			$ev = new UserEvent(
				$event->event_id,
				EEvent::$type(),
				$uid,
				new \DateTime($event->date),
				$uconnected,
				$event->event_data_connected
			);
			$events[] = $ev;
		}
		return $events;
	}
}