<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

use common\components\PostsService;
use common\components\PhotoService;

/**
 * Description of PostAttachment
 *
 * @author Mayumu
 */
class PostAttachment
{
    private $post_id;
    private $file;
    private $attachment_id;
    private $is_attachment;
    
    public function __construct($post_id)
    {
        $this->post_id = $post_id;
        $file_name = PostsService::getPostAttachmentPhoto($post_id);
        if(!$file_name)
        {
            $this->is_attachment = false;
        }
        else 
        {
            $this->is_attachment = true;
            $this->file = PhotoService::getPostAttachmentPhoto($file_name);
        }
    }
    
    public function getFile()
    {
        if($this->is_attachment)
            return $this->file;
        else
            return null;
    }
    
    public function getAttachmentId()
    {
        if($this->is_attachment)
            return $this->attachment_id;
        else
            return null;
    }
    
    public function getPostId()
    {
        if($this->is_attachment)
            return $this->post_id;
        else
            return null;
    }
}
