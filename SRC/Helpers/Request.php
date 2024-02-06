<?php
namespace Marinha\Mvc\Helpers;

class Request
{
    public static function get(){
       
        return $_SERVER['REQUEST_METHOD'];
    }
}
