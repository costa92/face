<?php
namespace Costa92\Face\Services;
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2016/12/19
 * Time: 上午9:39
 */
use Costa92\Face\Http;
class FaceService
{
    public $_Http;
    public function __construct()
    {
        $this->_Http = new Http();
    }

    public function detect(){
        echo 121;
    }

}