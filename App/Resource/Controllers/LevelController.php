<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-27
 * Time: 05:08
 */

namespace App\Resource\Controllers;

use App\Core\View\View;
use App\Core\Request\Request;
use App\Resource\Models\LevelModel;
use App\Core\Response\Response;

class LevelController extends Controller
{
    /**
     * LevelController constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->model = new LevelModel();
    }

    /**
     * get all levels
     */
    public function all(): void
    {
        $levels = $this->model->getAllRecords('levels');
        $this->prepDataToReturn($levels,'level',true);
    }

    /**
     * Get single level
     *
     * @param int $id
     */
    public function single(int $id): void
    {
        $levels = $this->model->getSingleRecord('levels','id',$id);
        $this->prepDataToReturn($levels,'level',true);
    }

    /**
     * Save new level in DB
     */
    public function storeNewLevel(): void
    {
        View::response($this->model->addNewLevelData($this->request->getPostData()));
    }

}