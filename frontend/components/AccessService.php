<?php

namespace app\components;

use Yii;
use app\components;
use app\components\exceptions\InvalidUserException;
use app\components\exceptions\InvalidEnumKeyException;
use app\components\exceptions\InvalidLocationException;
use MyCLabs\Enum\Enum;

class AccessService
{

    private static $LocationsPermissions;
    private static $location;

    public static function check($perm)
    {
        if(!(Permission::isValid($perm)))
        {
            throw new InvalidEnumKeyException();
        }
        self::matchLocation();
        $userID = Yii::$app->user->getId();
        
        $value = self::judgePermission($perm);

        return $value;
    }
    
    public static function hasAccess($check_id, $objectCheckType)
    {
        if(!ObjectCheckType::isValid($objectCheckType))
        {
            throw new InvalidEnumKeyException();
        }
        
        $myId = Yii::$app->user->getId();
        
        switch($objectCheckType)
        {
            case ObjectCheckType::Request:
                return self::__ownerCheck_typeRequest($check_id);
            case ObjectCheckType::Post:
                return self::__ownerCheck_typePost($check_id);
            case ObjectCheckType::PostComment:
                return self::__ownerCheck_typePostComment($check_id);
            default:
                throw new exceptions\FeatureNotImplemented("Check Type: " . $objectCheckType . ". That function cannot check that data object yet");
        }
    }
    
    private static function __ownerCheck_typeRequest($check_id)
    {
        $user = Yii::$app->user->getId();
        return ($check_id === $user);
    }
    
    private static function __ownerCheck_typePost($receiver_id)
    {
        $user = Yii::$app->user->getId();
        if($user == $receiver_id)
        {
            return true;
        }
        
        return RelationService::isFriend($user, $receiver_id);
    }
    
    private static function __ownerCheck_typePostComment($post_id)
    {
        $post = \app\models\Post::find()
                ->select(['user_id', 'owner_id'])
                ->where(['post_id' => $post_id])
                ->one();
        if(!is_null($post))
        {
            if($post['user_id'] == $post['owner_id'])
            {
                return true;
            }
            else
            {
                return RelationService::isFriend($post['user_id'], $post['owner_id']);
            }
        }
        else
        {
            return false;
        }
    }

    private static function matchLocation()
    {
        $controller = \Yii::$app->controller->id;
        $action = \Yii::$app->controller->action->id;
        
        $searchRegex = $controller . "|" . $action;
        $key = Location::search($searchRegex);
        
        if(!is_string($key))
        {
            throw new InvalidLocationException("Error: Regex: " . $searchRegex);
        }
        
        $locs = Location::toArray();
        self::$location = $locs[$key];
        
    }
    private static function judgePermission($valueToCheck)
    {
        self::SetLocationsPermissions();
        if (in_array($valueToCheck, self::$LocationsPermissions[Location::GLOBAL_ALL]))
        {
            return true;
        }
        if (in_array($valueToCheck, self::$LocationsPermissions[self::$location]))
        {
            return true;
        }
        if (in_array($valueToCheck, self::$LocationsPermissions[(Yii::$app->user->isGuest == true ? Location::GLOBAL_notLogged : Location::GLOBAL_logged)]))
        {
            return true;
        }
        
        
        return false;
    }

    private static function SetLocationsPermissions()
    {
        self::$LocationsPermissions = Location::values();
        self::$LocationsPermissions = self::ResizeOneDimensionalToTwoDimensionalArray(self::$LocationsPermissions);
        ////////////////////////////////////////////////////////////////////////

        self::$LocationsPermissions[Location::GLOBAL_ALL] = [
            Permission::UseSearch,
        ];
        self::$LocationsPermissions[Location::GLOBAL_logged] = [
            Permission::SendPrivateMessage,
            Permission::RemovePostOwnComment,
            Permission::ChangePostOwnComment,
            Permission::ChangeOwnPost,
        ];

        self::$LocationsPermissions[Location::GLOBAL_notLogged] = [
            Permission::CreateAccount,
            Permission::LoginPermission,
        ];

        self::$LocationsPermissions[Location::ActionChangeLanguage] = [
            Permission::ChangeLanguage,
        ];

        self::$LocationsPermissions[Location::ForgotPasswordPage] = [
        ];

        self::$LocationsPermissions[Location::HomePage] = [
            Permission::LoginPermission,
        ];

        self::$LocationsPermissions[Location::LoggedHomePage] = [
        ];

        self::$LocationsPermissions[Location::LoginPage] = [
        ];

        self::$LocationsPermissions[Location::MyProfiePage] = [
            Permission::ChangeAccountInfo,
            Permission::AddPost,
            Permission::AddPostComment,
            Permission::ChangePost,
            Permission::ChangeProfileDetails,
            Permission::ChangeProfilePhoto,
            Permission::RemovePhoto,
            Permission::RemovePost,
            Permission::RemovePostComment,
        ];

        self::$LocationsPermissions[Location::RegisterPage] = [
            Permission::CreateAccount,
        ];

        self::$LocationsPermissions[Location::UserProfilePage] = [
            Permission::AddPost,
            Permission::AddPostComment,
            Permission::ChangePostOwnComment,
            Permission::ManageUserRelations,
        ];
    }

    private static function ResizeOneDimensionalToTwoDimensionalArray($Array)
    {
        $array = array();
        foreach (array_reverse($Array) as $arr)
        {
            $array[(string) $arr] = array();
        }
        return $array;
    }

}

/**
 * AccessService
 */
class Permission extends Enum
{

    const ChangeProfilePhoto = "PHOTO.PROFILECHANGE";
    const AddPhoto = "PHOTO.ADD";
    const RemovePhoto = "PHOTO.REMOVE";
    const AddPost = "POST.WALL";
    const ChangePost = "POST.WALLCHANGE"; // global for moderators and for WALL owners
    const ChangeOwnPost = "POST.OWNCHANGE";
    const RemovePost = "POST.WALLREMOVE";
    const AddPostComment = "POST.ADDCOMMENT";
    const ChangePostComment = "POST.CHANGECOMMENT"; // global for moderators etc.
    const ChangePostOwnComment = "POST.CHANGECOMMENT.OWN";
    const RemovePostComment = "POST.REMOVECOMMENT";
    const RemovePostOwnComment = "POST.REMOVECOMMENT.OWN";
    const ChangeProfileDetails = "PROFILE.CHANGEDETAILS";
    const ChangeAccountInfo = "ACCOUNT.CHANGE";
    const SendPrivateMessage = "PM.SEND";
    const RequestFriendship = "FRIEND.REQ";
    const ChangeLanguage = "ACTION.CHANGELANG";
    const LoginPermission = "GLOBAL.LOGINPERMISSION";
    const CreateAccount = "SYSTEM.CREATEUSER";
    const ManageUserRelations = "USER.RELATIONS";
    const UseSearch = "SYSTEM.SEARCH";

}

/**
 * AccessService
 */
class Location extends Enum
{
    const GLOBAL_logged = "GLOBAL.L";
    const GLOBAL_notLogged = "GLOBAL.NL";
    const GLOBAL_ALL = "GLOBAL";
    //NOT LOGGED
    const HomePage = "site|index";
    const RegisterPage = "site|signup";
    const LoginPage = "site|login";
    const ForgotPasswordPage = "Site|requestPasswordReset"; //TODO it needs to be checked
    //MIXED
    const ActionChangeLanguage = "action|lang";
    
    //LOGGED
    
    const MyProfiePage = "intouch|profile";
    const UserProfilePage = "intouch|userprofile";
    const LoggedHomePage = "intouch|index";
    const SearchLogged = "intouch|search";

}

class ObjectCheckType extends Enum
{
    const Post = "PostService|Post";
    const PostComment = "PostService|Comment";
    const Relation = "RelationService|Relation";
    const Request = "RequestService|Request";
    const Photo = "PhotoService|Photo";
}
