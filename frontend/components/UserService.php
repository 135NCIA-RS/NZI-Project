<?php

namespace app\components;

use app\models\UserInfo;
use common\models\User;
use app\components\exceptions\InvalidDateException;
use app\components\exceptions\InvalidUserException;

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

    /**
     * Returns name of the user for the specified index
     * @param int $id User's ID 
     * @return string|boolean : User's Name (not username) or false
     */
    public static function getName($id)
    {
        $data = UserInfo::find()
                ->select('user_name')
                ->where(['user_id' => $id])
                ->one();
        return isset($data['user_name']) ? $data['user_name'] : false;
    }

    /**
     * Returns surname of the user for the specified index
     * @param int $id User's ID
     * @return string|boolean : User's Surname or false on error
     */
    public static function getSurname($id)
    {
        $data = UserInfo::find()
                ->select('user_surname')
                ->where(['user_id' => $id])
                ->one();
        return isset($data['user_surname']) ? $data['user_surname'] : false;
    }

    /**
     * Returns Full User Name in format "Name Surname"
     * @param int $id User's ID
     * @return string Name + Surname
     */
    public static function getNameLong($id)
    {
        $surname = self::getSurname($id);
        $name = self::getName($id);

        if(is_bool($name) || is_bool($surname))
        {
            return false;
        }
        if (strlen($name) == 0 || strlen($surname) == 0)
        {
            return "Uzupelnij dane";
        }
        return $name . " " . $surname ; // Surname + name
    }

    /**
     * Returns Birthdate or DateTime object with birthdate or false for specified user's index
     * @param int $id User's ID
     * @param string $option mask (eg. "Y-m-d"), or "object" (DateTime), or nothing
     * @return string|boolean|DateTime
     */
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

    /**
     * Returns email for specified user's id (or false)
     * @param int $id User's ID
     * @return string|boolean email or false on error
     */
    public static function getEmail($id)
    {
        $data = User::find()
                ->select("email")
                ->where(['id' => $id])
                ->one();
        return isset($data['email']) ? $data['email'] : false;
    }

    /**
     * Returns username for specified user's id (or false)
     * @param int $id User's ID
     * @return string|boolean Username or false on error
     */
    public static function getUserName($id)
    {
        $data = User::find()
                ->select("username")
                ->where(['id' => $id])
                ->one();
        return isset($data['username']) ? $data['username'] : false;
    }

    /**
     * Returns DateTime object for specified date
     * @param string $text date in format Y-m-d (1993-06-24)
     * @return DateTime Obj with birth date inside
     * @throws InvalidDateException
     */
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

    /**
     * Returns date in specified format
     * @param string $date Date
     * @param string $mask PHP Date format eg. Y-m-d
     * @return string Date in specified mask
     * @throws InvalidDateException
     */
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

    /**
     * Sets name for specified User index
     * @param int $id User's ID
     * @param string $name User's new name (NOT USERNAME)
     * @return boolean true on success, false on fail
     */
    public static function setName($id, $name)
    {
        $profile = UserInfo::findOne($id);

        if ($profile == null)
        {
            $profile = new UserInfo();
            $profile->user_id = $id;
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

    /**
     * Sets surname for specified user's ID
     * @param int $id User's ID
     * @param string $surname User's new surname
     * @return boolean true on success, false on fail
     */
    public static function setSurname($id, $surname)
    {
        $profile = UserInfo::findOne($id);
        if ($profile == null)
        {
            $profile = new UserInfo();
            $profile->user_id = $id;
        }
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

    /**
     * Sets email for specified user IF user exists
     * @param int $id User's ID
     * @param string $email User's email
     * @return boolean true on success, false on SQL fail
     * @throws InvalidUserException if User does not exist
     */
    public static function setEmail($id, $email)
    {
        $profile = User::findOne($id);
        if ($profile == null)
        {
            throw new InvalidUserException();
        }
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

    /**
     * Sets Birth Date for specified user's id
     * @param int $id User's ID
     * @param string $date Birth Date
     * @return boolean true on success, false on SQL fail
     * @throws InvalidDateException if $date is not date
     */
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
        if ($profile == null)
        {
            $profile = new UserInfo();
            $profile->user_id = $id;
        }
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

    /**
     * Sets new password for specified user's id
     * @param int $id User's ID
     * @param string $password User's new password
     * @return boolean true on success, false on fail
     * @throws InvalidUserException if user does not exist
     */
    public static function setPassword($id, $password)
    {
        $user = User::findOne($id);
        if ($user == null)
        {
            throw new InvalidUserException();
        }

        $user->password = $password;
        if ($user->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns Profile Education Info for specified ID
     * @param int $id User's ID
     * @return string|boolean Value or false on error
     */
    public static function getUserEducation($id)
    {
        $data = UserInfo::find()
                ->select('user_education')
                ->where(['user_id' => $id])
                ->one();
        return isset($data['user_education']) ? $data['user_education'] : false;
    }

    /**
     * Returns City for specified User's ID
     * @param int $id User's ID
     * @return string|boolean Value or false on error
     */
    public static function getUserCity($id)
    {
        $data = UserInfo::find()
                ->select('user_city')
                ->where(['user_id' => $id])
                ->one();
        return isset($data['user_city']) ? $data['user_city'] : false;
    }

    /**
     * Returns About section for specified user's ID
     * @param int $id User's ID
     * @return string|boolean Value or false on error
     */
    public static function getUserAbout($id)
    {
        $data = UserInfo::find()
                ->select('user_about')
                ->where(['user_id' => $id])
                ->one();
        return isset($data['user_about']) ? $data['user_about'] : false;
    }

    /**
     * Sets Education section for specified user's ID
     * @param int $id User's ID
     * @param string $edu Education value
     * @return boolean true on success, false on fail
     */
    public static function setUserEducation($id, $edu)
    {
        $profile = UserInfo::findOne($id);
        if ($profile == null)
        {
            $profile = new UserInfo();
            $profile->user_id = $id;
        }
        $profile->user_education = $edu;
        if ($profile->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Sets about section (user profile) for specified user's id
     * @param int $id User's ID
     * @param string $about About value
     * @return boolean true on success, false on fail
     */
    public static function setUserAbout($id, $about)
    {
        $profile = UserInfo::findOne($id);
        if ($profile == null)
        {
            $profile = new UserInfo();
            $profile->user_id = $id;
        }
        $profile->user_about = $about;
        if ($profile->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Sets City section (User's profile) for specified user's ID
     * @param int $id User's ID
     * @param string $city City value
     * @return boolean true on success, false on fail
     */
    public static function setUserCity($id, $city)
    {
        $profile = UserInfo::findOne($id);
        if ($profile == null)
        {
            $profile = new UserInfo();
            $profile->user_id = $id;
        }
        $profile->user_city = $city;
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
