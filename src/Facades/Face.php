<?php
namespace Costa92\Face\Facades;
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2016/12/19
 * Time: 上午10:04
 */

use Illuminate\Support\Facades\Facade;
class Face extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'FaceService';
    }
}