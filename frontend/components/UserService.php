<?php

namespace app\components;

use app\models\UserInfo;
use common\models\User;
use app\components\exceptions\InvalidDateException;

class UserService
{

    private $id;
    public $name;
    public $surname;
    public $dateofbirth;
    public $email;

    public function __construct($user_id)
    {
        $this->id = $user_id;
        $this->name = $this->getName($user_id);
        $this->surname = $this->getSurname($user_id);
        $this->dateofbirth = $this->getBirthDate($user_id);
        $this->email = $this->getEmail($user_id);
    }

    public function getId()
    {
        return $this->id;
    }

    public static function getName($id)
    {
        $user = UserInfo::find()
                ->select('user_name')
                ->where(['user_id' => $id])
                ->one();
        return isset($data['user_name']) ? $data['user_name'] : false;
    }

    public static function getSurname($id)
    {
        $data = UserInfo::find()
                ->select('user_surname')
                ->where(['user_id' => $this->id])
                ->one();
        return isset($data['user_surname']) ? $data['user_surname'] : false;
    }

    public static function getNameLong($id)
    {
        return "TODO"; // Surname + name
    }

    public static function getBirthDate($id, $option = "default")
    {
        $data = UserInfo::find()
                ->select("user_birthdate")
                ->where(['user_id' => $id])
                ->one();

        if (!isset($data["user_birthdate"]))
            return false;

        switch ($option)
        {
            case 'object':
                try
                {
                    return self::getDateObj($text);
                }
                catch (InvalidDateException $ex)
                {
                    //is not date (wtf, someone has edited db manually)
                    return false;
                }
                break;
            case 'default':
                return isset($data['user_birthdate']) ? $data['user_birthdate'] : false;
            default:
                try
                {
                    return self::getMaskedDate($data["user_birthdate"], $option);
                }
                catch (InvalidDateException $ex)
                {
                    return false;
                }
        }
    }

    public static function getEmail($id)
    {
        $data = User::find()
                ->select("email")
                ->where(['id' => $id])
                ->one();
        return isset($data['email']) ? $data['email'] : false;
    }

    public static function getUserName($id)
    {
        $data = User::find()
                ->select("username")
                ->where(['id' => $id])
                ->one();
        return isset($data['username']) ? $data['username'] : false;
    }

    private static function getDateObj($text)
    {
        if (strtotime($text) !== false)
        {
            return \DateTime::createFromFormat('Y-m-d', $text);
        }
        else
        {
            throw new InvalidDateException("INVALID BIRTH DATE IN DATABASE", 1);
        }
    }

    private static function getMaskedDate($date, $mask)
    {
        try
        {
            $date = self::getDateObj($date);
        }
        catch (Exception $ex)
        {
            throw new InvalidDateException("INVALID DATE");
        }
        return $date->format($mask);
    }

    public static function setName($id, $name)
    {
        $profile = UserInfo::findOne($id);
        if($profile == null)
        {
            $profile = new UserInfo();
            $profile->user_id = $id;
            $profile->user_birthdate = "";
        }
        $profile->user_name = $name;
        if ($profile->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function setSurname($id, $surname)
    {
        $profile = UserInfo::findOne($id);
        $profile->user_surname = $surname;
        if ($profile->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function setEmail($id, $email)
    {
        $profile = User::findOne($id);
        $profile->email = $email;
        if ($profile->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function setBirthDate($id, $date)
    {
        //validate
        if (($timestamp = strtotime($date)) !== false)
        {
            $dt = date('Y-m-d', $timestamp);
        }
        else
        {
            throw new InvalidDateException("INVALID BIRTH DATE", 2);
        }
        //
        $profile = UserInfo::find()->where(['user_id' => $id])->one();
        $profile->user_birthdate = $dt;
        if ($profile->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    

}
