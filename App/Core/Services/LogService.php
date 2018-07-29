<?php

namespace App\Core\Services;

class LogService
{
    /**
     * Save log in file
     *
     * @param int $type error type: (1=ERROR, 2=WARNING, 3=INFO)
     * @param string $msg error message to save in log
     * @return void
     */
    public static function addLog(int $type, string $msg=null): void
    {
        file_put_contents(PATH.'/../app/logs/logs.txt',"\n"."**".LogService::getLogType($type)."** TIME: ".date("Y-m-d H:i:s").
                          "|| REQUEST TYPE: ".$_SERVER['REQUEST_METHOD']." || URI: ".$_SERVER['REQUEST_URI'].
                          "|| CONTENT: "."|| MESSAGE: ".$msg."\n", FILE_APPEND);
    }

    /**
     * Return error type base on provide code number
     *
     * @param int $code
     * @return string
     */
    private static function getLogType(int $code): string
    {
        switch($code){
            case 1:
                return "INFO";
                break;
            case 2:
                return "WARNING";
                break;
            case 3:
                return "ERROR";
                break;
            default:
                return "NO SET";
                break;
        }
    }

}