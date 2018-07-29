<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-01-16
 * Time: 19:01
 */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

define('PATH',str_replace("\\","/",dirname(__FILE__)).'/');
require("../vendor/autoload.php");

require ("../App/Core/Bootstrap.php");