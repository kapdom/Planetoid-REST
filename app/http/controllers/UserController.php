<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-23
 * Time: 17:07
 */

namespace App\Http\Controllers;

use App\Core\DB\DB;
use App\Core\Request\Request;
use App\Core\Services\Response;
use App\Core\View\View;
use App\Http\Db\LevelDb;
use App\Http\db\UserDb;

class UserController extends Controller
{
    /**
     * UserController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get all users from DB
     */
    public function all(): void
    {
        $users = DB::getAllRecords('users');
        $this->parseDataToReturn($users,'user',true);
    }

    /**
     * Get single user by specific value
     *
     * @param $id int|string user ID
     */
    public function single($id): void
    {
        $user = DB::getSingleRecord('users','id',$id);
        $this->parseDataToReturn($user,'user',true);
    }

    /**
     * @param $userId
     * @param $levelId
     */
    public function userLevelSingle($userId,$levelId)
    {
        $results = UserDb::getSingleUserLevelResult($userId,$levelId);
        if (!empty($results)) {
            View::response(Response::encodeDataAsJson($results));
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
       View::response(UserDb::saveNewUser($this->request->getPostData()));
    }

    /**
     * Update user last login time
     *
     * @param $id
     */
    public function updateLoginTime($id): void
    {
        View::response(UserDb::updateUserLoginTime($id));
    }

    /**
     * Update user level result
     * @param $id
     */
    public function updateLevelResult($id)
    {
        View::response(UserDb::updateLevelResult($id, $this->request->getPutData()));
    }
}