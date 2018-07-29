<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-23
 * Time: 17:00
 */

namespace App\Resource\Controllers;

use App\Core\DB\DB;
use App\Core\View\View;
use App\Core\Request\Request;
use App\Core\Response\Response;

abstract class Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var DB
     */
    protected $model;

    /**
     * @param array $data
     * @param string $entity
     * @param bool $json
     */
    protected function prepDataToReturn(array $data, string $entity, bool $json=false)
    {
        if (!empty($data)){
            View::response($this->model->createObjectsCollectionToDisplay($data,$entity,$json));
        } else {
            View::abort(404,'No data to return');
        }
    }

}