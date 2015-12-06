<?php

namespace app\components;

use app\models\UserInfo;
use common\models\User;

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
        return isset($data['user_name']) ? $data['user_name'] : null;
    }

    public static function getSurname($id)
    {
        $data = UserInfo::find()
                        ->select('user_surname')
                        ->where(['user_id' => $this->id])
                        ->one();
        return isset($data['user_surname']) ? $data['user_surname'] : null;
    }
    
    public static function getNameLong($id)
    {
        return "TODO"; // Surname + name
    }
    
    public static function getBirthDate($id)
    {
        $data =  UserInfo::find()
                ->select("user_birthdate")
                ->where(['user_id' => $id])
                ->one();
        return isset($data['user_birthdate']) ? $data['user_birthdate'] : null;
    }
    
    public static function getEmail($id)
    {
        $data = User::find()
                ->select("email")
                ->where(['id' => $id])
                ->one();
        return isset($data['email']) ? $data['email'] : null;
    }
    
    public static function getUserName($id)
    {
        $data =  User::find()
                ->select("username")
                ->where(['id' => $id])
                ->one();
        return isset($data['username']) ? $data['username'] : null;
    }

}
