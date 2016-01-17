<?php

namespace app\components;
use app\models\Request;
use app\components\RelationService;
use app\components\RelationType;
use common\models\User;
use app\components\exceptions\InvalidDateException;
use app\components\exceptions\InvalidUserException;
use app\components\exceptions\InvalidEnumKeyException;
use MyCLabs\Enum\Enum;

class RequestService
{

    /**
     * Creating a request 
     * @param type $user1_id User who added id
     * @param type $user2_id User whos adding id
     * @param \app\components\RequestType $req_type type of request etc friend_request
     * @param type $date date of added
     * @return boolean false if reqtype is null, 
     */
    public static function createRequest($user1_id, $user2_id, $req_type, $date)
    {
        
        $check = Request::find()
                ->select('req_id')
                ->where(['user1_id' => $user1_id, 'user2_id' => $user2_id])
                ->one();
        if (!is_null($check))
            return true;
        
        if(!RequestType::isValid($req_type))
        {
            throw new InvalidEnumKeyException("ERROR, VAL: " . $req_type);
        }
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

    /**
     * answering on request, accept or dismiss
     * @param type $req_id 
     * @param type $answer answer on request true or false.
     */
    public static function answerRequest($req_id, $answer)
    {
        if ($answer)
        {
            $user1_id = RequestService::getUser1Id($req_id);
            $user2_id = RequestService::getUser1Id($req_id);
            RelationService::setRelation($user1_id, $user2_id, RelationType::Friend);
        }
        self::dropRequest($req_id);
    }

    /**
     * dropping a request//private function
     * @param type $req_id
     */
    private static function dropRequest($req_id)
    {
        $data = Request::findOne($req_id);
        $data->delete();
    }

    /**
     * getting user1 ID 
     * @param type $req_id
     * @return user 1 ID
     */
    public static function getUser1Id($req_id)
    {
        $data = Request::find()
                ->select('user1_id')
                ->where(['req_id' => $req_id])
                ->one();
        return isset($data['user1_id']) ? $data['user1_id'] : false;
    }

    /**
     *  getting user 2 ID
     * @param type $req_id
     * @return type User 2 ID
     */
    public static function getUser2Id($req_id)
    {
        $data = Request::find()
                ->select('user2_id')
                ->where(['req_id' => $req_id])
                ->one();
        return isset($data['user2_id']) ? $data['user2_id'] : false;
    }

    /**
     * Getting Request Date
     * @param type $req_id
     * @return date of Request
     */
    public static function getRequestDate($req_id)
    {
        $data = Request::find()
                ->select('date')
                ->where(['req_id' => $req_id])
                ->one();
        return isset($data['date']) ? $data['date'] : false;
    }

}

class RequestType extends Enum
{
    const FriendRequest = "friend";
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

