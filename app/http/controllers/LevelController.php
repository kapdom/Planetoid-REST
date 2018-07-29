<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-27
 * Time: 05:08
 */

namespace App\Http\Controllers;

use App\Core\DB\DB;
use App\Core\View\View;
use App\Core\Request\Request;
use App\Http\Db\LevelDb;

class LevelController extends Controller
{
    /**
     * UserController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function all()
    {
        $levels = DB::getAllRecords('levels');
        $this->parseDataToReturn($levels,'level',true);
    }

    public function single($id)
    {
        $levels = DB::getSingleRecord('levels','id',$id);
        $this->parseDataToReturn($levels,'level',true);
    }

    public function storeNewLevel()
    {
        View::response(LevelDb::addNewLevelData($this->request->getPostData()));
    }

}