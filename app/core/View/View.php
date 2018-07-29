<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-26
 * Time: 17:34
 */

namespace App\Core\View;

use App\Core\Request\Request;

class View
{
    /**
     * Send Response data
     *
     * @param $data
     */
    public static function response($data): void
    {
        echo $data;
        self::returnCodeHeader(200,'OK');
        self::returnContentType();
    }

    /**
     * Create error response header
     *
     * @param int $code
     * @param string|null $msg
     */
    public static function abort(int $code, string $msg=null):void
    {
        self::returnCodeHeader($code,$msg);
    }

    /**
     * Create header code
     *
     * @param int $code Code to return
     * @param string $message message to return
     * @return void
     */
    private static function returnCodeHeader(int $code, string $message=null):void
    {
        header("HTTP/1.1 ".$code." ".$message, true, $code);
    }

    /**
     * Set return content type header
     */
    private static function returnContentType():void
    {
        header("Content-Type:".Request::getReturnContentTypeHeader());
    }

}