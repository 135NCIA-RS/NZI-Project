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
use app\components\AccessService;

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

        if (!RequestType::isValid($req_type))
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
        $user1_id = RequestService::getUser1Id($req_id);
        $user2_id = RequestService::getUser2Id($req_id);

        ///AccessService
        try
        {
            if (!AccessService::hasAccess($user2_id, ObjectCheckType::Request))
            {
                Yii::$app->session->setFlash('error', 'Access Denied');
                return false;
            }
        }
        catch (Exception $ex)
        {
            Yii::$app->session->setFlash('warning', 'Something went wrong, contact Administrator');
            return false;
        }
        ///end AccessService

        if ($answer)
        {
            RelationService::setRelation($user1_id, $user2_id, RelationType::Friend);
        }
        self::dropRequest($req_id);

        //TODO Przemek popraw to!
        $check = Request::find()
                ->select('req_id')
                ->where(['user1_id' => $user2_id, 'user2_id' => $user1_id, 'req_type' => 'friend'])
                ->one();
        if (!is_null($check))
        {
            self::dropRequest($check['req_id']);
        }
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
     * 
     * @param type $user2_id user who is logged.
     * @return table with request to logged user.
     */
    public static function getMyRequests($user2_id) //TODO!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!DONT WORK
    {
        /* Kawlek kodu od grzesia z Discorda
         * $dane = [];
          foreach(...)
          {
          $dane[] = createReqObj(...);
          }

          return $dane;
         */
        $arr = [];
        $rel = Request::find()
                ->where([
                    'user2_id' => $user2_id,
                    'req_type' => RequestType::FriendRequest
                ])
                ->all();
        if (is_null($rel))
        {
            return [];
        }
        foreach ($rel as $var)
        {
            $arr[] = self::createReqObj($var['user1_id'], $var['date'], $var['req_id'], $var['req_type']);
        }
        return $arr;
    }

    private static function createReqObj($user1_id, $date, $req_id, $req_type)
    {
        $uname = UserService::getUserName($user1_id);
        $fullname= UserService::getNameLong($user1_id);
        return ['type' => $req_type, 'req_id' => $req_id, 'senderUserName' => $uname, 'date' => $date, 'fullname'=>$fullname];
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

    public static function isRequestBetween($user1_id, $user2_id, $req_type)
    {
        if (!UserService::existUser($user1_id))
            throw new InvalidUserException("User: " . $user1_id);
        if (!UserService::existUser($user2_id))
            throw new InvalidUserException("User: " . $user2_id);
        if (!(RequestType::isValid($req_type)))
            throw new InvalidEnumKeyException("Value: " . $req_type);

        return Request::find()
                        ->where(['user1_id' => $user1_id, 'user2_id' => $user2_id])
                        ->orWhere(['user1_id' => $user2_id, 'user2_id' => $user1_id])
                        ->exists();
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

