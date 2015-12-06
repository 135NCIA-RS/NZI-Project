<?php

namespace app\components\exceptions;
use Yii;
use yii\base\ErrorException;

class InvalidDateException extends ErrorException
{
    protected $ErrorNo;
    protected $message;
    
    public function __construct($message = "ERROR" ,$errNo = 0)
    {
        $this->message = $message;
        $this->ErrorNo = $errNo;
        
    }
    
    public function getInfo()
    {
        return "ERROR : " . $this->ErrorNo . " | " . $this->message . PHP_EOL;
    }
}

