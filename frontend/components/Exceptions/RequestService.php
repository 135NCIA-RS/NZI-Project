<?php

namespace app\components;

use app\models\Request;
use app\components\RelationService;
use app\components\RelationType;
use common\models\User;
use app\components\exceptions\InvalidDateException;
use app\components\exceptions\InvalidUserException;

class RequestService
{

    /**
     * 
     * @param type $user1_id User who added id
     * @param type $user2_id User whos adding id
     * @param \app\components\RequestType $req_type type of request etc friend_request
     * @param type $date date of added
     * @return boolean false if reqtype is null, 
     */
    public static function createRequest($user1_id, $user2_id, RequestType $req_type, $date)
    {
        if (is_null($req_type))
            return false;
        $data = new Request();
        $data->req_type = $req_type;
        $data->user1_id = $user1_id;
        $data->user2_id = $user2_id;
        $data->date = $date;

        if ($data->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function answerRequest($req_id, $answer) //TODO!!!
    {
        if ($answer)
        {
            
            RelationService::setRelation($user1_id, $user2_id, RelationType::Friend);
        }
        self::dropRequest($req_id);
    }

    private static function dropRequest($req_id)
    {

        $data = Request::findOne($req_id);
        $data->delete();
    }

}

class RequestType extends SplEnum
{

    const __default = null;
    const FriendRequest = "friend";

}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

