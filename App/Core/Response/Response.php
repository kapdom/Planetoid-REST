<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-27
 * Time: 16:45
 */

namespace App\Core\Response;


class Response
{

    /**
     * Return as json string
     *
     * @param array $data
     * @return string
     */
    public function encodeDataAsJson(array $data): string
    {
        return json_encode($data);
    }

}