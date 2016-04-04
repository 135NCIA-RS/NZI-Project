<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 3/20/2016
 * Time: 3:36 PM
 */

namespace common\components;


interface ImageLocationInterface
{
    /**
     * @param Image $image Image to save
     * @return bool true on success, else false
     */
    public function save($data, $fileName, $subFolders = "");

    /**
     * @param Image $image Image to be removed
     * @return bool true on success, else false
     */
    public function remove($fileName, $subFolders = "");

    /**
     * @return Image[] List of images stored at location
     */
    public function listImages($subFolders = "");

    /**
     * @return string Url of Location eg. media.domain.com/images/
     */
    public function getUrl();

}