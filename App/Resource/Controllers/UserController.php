<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-23
 * Time: 17:07
 */

namespace App\Resource\Controllers;

use App\Core\Request\Request;
use App\Core\View\View;
use App\Resource\Models\UserModel;
use App\Core\Response\Response;

class UserController extends Controller
{
    /**
     * UserController constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->model = new UserModel();
    }

    /**
     * Get all users from DB
     */
    public function all(): void
    {
        $users = $this->model->getAllRecords('users');
        $this->prepDataToReturn($users,'user',true);

    }

    /**
     * Get single user by specific value
     *
     * @param $id int|string user ID
     */
    public function single($id): void
    {
        $user = $this->model->getSingleRecord('users','id',$id);
        $this->prepDataToReturn($user,'user',true);
    }

    /**
     * @param $userId
     * @param $levelId
     */
    public function userLevelSingle(int $userId, int $levelId): void
    {
        $results = $this->model->getSingleUserLevelResult($userId,$levelId);
        if (!empty($results)) {
            View::response($this->response->encodeDataAsJson($results));
        } else {
            View::abort(404,'No data to return');
        }
    }

    /**
     * Save new user in DB and return his id
     * If user already exist in DB return his id
     */
    public function storeUser(): void
    {
       View::response($this->model->saveNewUser($this->request->getPostData()));
    }

    /**
     * Update user last login time
     *
     * @param $id
     */
    public function updateLoginTime(int $id): void
    {
        View::response($this->model->updateUserLoginTime($id));
    }

    /**
     * Update user level result
     * @param $id
     */
    public function updateLevelResult(int $id): void
    {
        View::response($this->model->updateLevelResult($id, $this->request->getPutData()));
    }
}