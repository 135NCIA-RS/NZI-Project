<?php

namespace app\components;

use Yii;
use app\components\exceptions\InvalidUserException;

class AccessService
{
    /**
     * 
     * @param \app\components\Permission $perm
     * @param type $user_id  Check that ID
     * @param type $user2_id  On ProfileID 
     * @return boolean
     * @throws InvalidUserException
     */
    public static function check(Permission $perm, $user_id, $user2_id = null)
    {
        if (!(UserService::existUser($user1_id)))
        {
            throw new InvalidUserException("User1 cannot be found");
        }
        if ($user2_id != null)
        {
            if (!UserService::existUser($user2_id))
            {
                throw new InvalidUserException("User2 cannot be found");
            }
        }
        //////////////////
        
        if($user1_id === $user2_id)
        {
            return true;
        }

        switch ($perm)
        {
            case Permission::AddPhoto:
                break;
            case Permission::RemovePhoto:
                break;
            case Permission::AddPost:
                break;
            case Permission::ChangePost:
                break;
            case Permission::AddPostComment:
                break;
            case Permission::ChangePostComment:
                break;
            case Permission::RemovePostComment:
                break;
            case Permission::SendPrivateMessage:
                break;
            case Permission::RequestFriendship:
                break;
            default:
                return false;
        }
//        switch ($perm)
//        {
//            case Permission::ChangeProfilePhoto:
//                break;
//            case Permission::AddPhoto:
//                break;
//            case Permission::RemovePhoto:
//                break;
//            case Permission::AddPost:
//                break;
//            case Permission::ChangePost:
//                break;
//            case Permission::RemovePost:
//                break;
//            case Permission::AddPostComment:
//                break;
//            case Permission::ChangePostComment:
//                break;
//            case Permission::RemovePostComment:
//                break;
//            case Permission::ChangeProfileDetails:
//                break;
//            case Permission::ChangeAccountInfo:
//                break;
//            case Permission::SendPrivateMessage:
//                break;
//            case Permission::RequestFriendship:
//                break;
//        }

        return false;
    }

}

class Permission extends SplEnum
{
    const __default = null;
    //####$$$$####$$$$####$$$$####$$$$###$$$$
    const ChangeProfilePhoto = "PHOTO.PROFILECHANGE";
    const AddPhoto = "PHOTO.ADD";
    const RemovePhoto = "PHOTO.REMOVE";
    const AddPost = "POST.WALL";
    const ChangePost = "POST.WALLCHANGE";
    const RemovePost = "POST.WALLREMOVE";
    const AddPostComment = "POST.ADDCOMMENT";
    const ChangePostComment = "POST.CHANGECOMMENT";
    const RemovePostComment = "POST.REMOVECOMMENT";
    const ChangeProfileDetails = "PROFILE.CHANGEDETAILS";
    const ChangeAccountInfo = "ACCOUNT.CHANGE";
    const SendPrivateMessage = "PM.SEND";
    const RequestFriendship = "FRIEND.REQ";
}
