<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-23
 * Time: 08:12
 */

namespace Api\Core\Bootstrap;

use App\Core\DB\DB;
use App\Core\Router\Router;
use App\Core\Request\Request;

class Bootstrap
{
    /**
     * Bootstrap constructor.
     * Parse created routers
     */
    public function __construct()
    {
        require('../app/http/routers/webRoutes.php');
    }

    /**
     * Initialize DB connection and parse incoming request
     */
    public function start()
    {
        DB::initialize();
        Router::matchUrl();
    }
}