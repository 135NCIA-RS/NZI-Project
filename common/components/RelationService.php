<?php

namespace common\components;

use common\models\User;
use app\models\Relationship;
use common\components\exceptions\InvalidUserException;
use common\components\exceptions\InvalidRelationException;

class RelationService
{

    /**
     * Sets new relation between two users
     * @param int $user1_id
     * @param int $user2_id
     * @param string $rel_type RelationType's const
     * @return boolean true if ok, false if not
     * @throws InvalidRelationException if relation does not exist (USE RelationType!)
     * @throws InvalidUserException if user does not exist (one or both)
     */
    public static function setRelation($user1_id, $user2_id, $rel_type)
    {
        if (!RelationType::validate($rel_type))
        {
            throw new InvalidRelationException("Error. Something went wrong. Use RelationType class instead of value");
        }

        if (!(UserService::existUser($user1_id) && UserService::existUser($user2_id)))
        {
            throw new InvalidUserException("User1 or User2 or both cannot be found");
        }

        $rel1 = Relationship::find()
                ->where([
                    'user1_id'      => $user1_id,
                    'user2_id'      => $user2_id,
                    'relation_type' => $rel_type,
                ])
                ->one();
        $rel2 = Relationship::find()
                ->where([
                    'user1_id'      => $user2_id,
                    'user2_id'      => $user1_id,
                    'relation_type' => $rel_type,
                ])
                ->one();

        $mode = RelationMode::getMode($rel_type);

        if ($mode == RelationMode::OneWay)
        {
            if ($rel1 == null) //OneWay
            {
                if (self::saveRelation($user1_id, $user2_id, $rel_type))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true; // already exists;
            }
        }
        else // TwoWay
        {
            if ($rel1 == null && $rel2 == null) //TwoWay
            {
                if (self::saveRelation($user1_id, $user2_id, $rel_type))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true; //already exists
            }
        }
    }

    /**
     * Checks if User1 is friend of user2
     * @param int $user1_id User1's ID
     * @param int $user2_id User2's ID
     * @return boolean True if ok, else false
     * @throws InvalidUserException if User1 or User2 or both of them does not exist
     */
    public static function isFriend($user1_id, $user2_id)
    {
        if (!(UserService::existUser($user1_id) && UserService::existUser($user2_id)))
        {
            throw new InvalidUserException("User1 or User2 or both cannot be found");
        }

        $rel = Relationship::find()
                ->where([
                    'user1_id'      => $user1_id,
                    'user2_id'      => $user2_id,
                    'relation_type' => RelationType::Friend,
                ])
                ->orWhere([
                    'user1_id'      => $user2_id,
                    'user2_id'      => $user1_id,
                    'relation_type' => RelationType::Friend,
                ])
                ->one();
        if ($rel == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Checks if User1 is followed by user2
     * @param int $user1_id User1's ID
     * @param int $user2_id User2's ID
     * @return boolean True if ok, else false
     * @throws InvalidUserException if User1 or User2 or both of them does not exist
     */
    public static function isFollower($user1_id, $user2_id)
    {
        if (!(UserService::existUser($user1_id) && UserService::existUser($user2_id)))
        {
            throw new InvalidUserException("User1 or User2 or both cannot be found");
        }

        $rel = Relationship::find()
                ->where([
                    'user1_id'      => $user1_id,
                    'user2_id'      => $user2_id,
                    'relation_type' => RelationType::Follower,
                ])
                ->one();
        if ($rel == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Checks if User1 is blocked by user2
     * @param int $user1_id User1's ID
     * @param int $user2_id User2's ID
     * @return boolean True if ok, else false
     * @throws InvalidUserException if User1 or User2 or both of them does not exist
     */
    public static function isBlocked($user1_id, $user2_id)
    {
        if (!(UserService::existUser($user1_id) && UserService::existUser($user2_id)))
        {
            throw new InvalidUserException("User1 or User2 or both cannot be found");
        }

        $rel = Relationship::find()
                ->where([
                    'user1_id'      => $user1_id,
                    'user2_id'      => $user2_id,
                    'relation_type' => RelationType::Blocked,
                ])
                ->one();

        if ($rel == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Returns an Array of All Relations betweeen user1 and user2 (OneWay + isFriend)
     * @param int $user1_id User1's ID
     * @param int $user2_id User2's ID
     * @return array Array of Relations
     */
    public static function getRelations($user1_id, $user2_id)
    {
        $ret                         = [];
        $ret[RelationType::Blocked]  = self::isBlocked($user1_id, $user2_id);
        $ret[RelationType::Follower] = self::isFollower($user1_id, $user2_id);
        $ret[RelationType::Friend]   = self::isFriend($user1_id, $user2_id);
        return $ret;
    }

    /**
     * Returns an array of friends of User1
     * @param int $user1_id User's ID
     * @return int[] Array of User's ID
     */
    public static function getFriendsList($user1_id)
    {
        $arr  = [];
        $rel  = Relationship::find()
                ->where([
                    'user1_id'      => $user1_id,
                    'relation_type' => RelationType::Friend,
                ])
                ->all();
        $rel2 = Relationship::find()
                ->where([
                    'user2_id'      => $user1_id,
                    'relation_type' => RelationType::Friend,
                ])
                ->all();
        foreach ($rel as $var)
        {
            $arr[] = UserService::getUserById($var->user2_id);
        }
        foreach ($rel2 as $var)
        {
            $arr[] = UserService::getUserById($var->user1_id);
        }

        return $arr;
    }

    /**
     * Returns List of Users who follow User1
     * @param int $user1_id User's ID
     * @return int[] Array of User IDs
     */
    public static function getUsersWhoFollowMe($user1_id)
    {
        $arr = [];
        $rel = Relationship::find()
                ->where([
                    'user2_id'      => $user1_id,
                    'relation_type' => RelationType::Follower,
                ])
                ->all();
        foreach ($rel as $var)
        {
            $arr[] = $var['user1_id'];
        }
        return $arr;
    }

    /**
     * Returns an array of users who User1 follow
     * @param type $user1_id
     * @return array Array of User IDs
     */
    public static function getUsersWhoIFollow($user1_id)
    {
        $arr = [];
        $rel = Relationship::find()
                ->where([
                    'user1_id'      => $user1_id,
                    'relation_type' => RelationType::Follower,
                ])
                ->all();
        foreach ($rel as $var)
        {
            $arr[] = $var['user2_id'];
        }
        return $arr;
    }

    /**
     * Returns array of Users who are blocked by User1
     * @param int $user1_id User1's ID
     * @return array
     */
    public static function getUsersBlockedByUser($user1_id)
    {
        $arr = [];
        $rel = Relationship::find()
                ->where([
                    'user1_id'      => $user1_id,
                    'relation_type' => RelationType::Blocked,
                ])
                ->all();
        foreach ($rel as $var)
        {
            $arr[] = $var['user2_id'];
        }
        return $arr;
    }

    public static function removeRelation($user1_id, $user2_id, $rel_type)
    {
        if (!RelationType::validate($rel_type))
        {
            throw new InvalidRelationException("Error. Something went wrong. Use RelationType class instead of value");
        }

        if (!(UserService::existUser($user1_id) && UserService::existUser($user2_id)))
        {
            throw new InvalidUserException("User1 or User2 or both cannot be found");
        }

        $dt = Relationship::find()->where([
                    'user1_id'      => $user1_id,
                    'user2_id'      => $user2_id,
                    'relation_type' => $rel_type
                ])->one();
        $dt->delete();

        if (RelationMode::getMode($rel_type) == RelationMode::TwoWay)
        {
            $dt1 = $dt  = Relationship::find()->where([
                        'user1_id'      => $user2_id,
                        'user2_id'      => $user1_id,
                        'relation_type' => $rel_type
                    ])->one();
            $dt1->delete();
        }
    }

    /**
     * Do not use that function (Only for setRelation purposes)
     */
    private static function saveRelation($user1_id, $user2_id, $rel_type)
    {
        $relation                = new Relationship();
        $relation->user1_id      = $user1_id;
        $relation->user2_id      = $user2_id;
        $relation->relation_type = $rel_type;
        if ($relation->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}

class RelationType
{

    const Friend   = "friend";
    const Follower = "follower";
    const Blocked  = "blocked";

    /**
     * Validates if Relation Type is Valid
     * @param type $rel Ralation Type to validate
     * @return boolean True if is ok, false if not
     */
    static function validate($rel)
    {
        switch ($rel)
        {
            case self::Friend:
            case self::Follower:
            case self::Blocked:
                return true;
                break;
            default:
                return false;
                break;
        }
    }

}

class RelationMode
{

    const OneWay = 1;
    const TwoWay = 2;

    /**
     * Returns Mode of Relation (OneWay/TwoWay)
     * @param type $relation_type
     * @return RelationMode's Const
     * @throws InvalidRelationException If relation type is invalid
     */
    public static function getMode($relation_type)
    {
        switch ($relation_type)
        {
            case RelationType::Friend:
                return self::TwoWay;
                break;
            case RelationType::Follower:
            case RelationType::Blocked:
                return self::OneWay;
                break;
            default:
                throw new InvalidRelationException();
        }
    }

}
