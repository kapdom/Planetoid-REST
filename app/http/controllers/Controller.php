<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-23
 * Time: 17:00
 */

namespace App\Http\Controllers;

use App\Core\View\View;
use App\Core\Services\Response;
use App\Core\Request\Request;

abstract class Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Parse data to return as a collection of objects
     *
     * @param array $data
     * @param string $objectModel
     * @param bool $json
     */
    protected function parseDataToReturn(array $data, string $objectModel, bool $json=false)
    {
        if(!empty($data)) {
            View::response(Response::createObjectsCollectionToDisplay($data, $objectModel, $json));
        }else{
            View::abort(404,'No data to return');
        }
    }

}