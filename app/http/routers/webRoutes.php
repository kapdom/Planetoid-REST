<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-23
 * Time: 09:18
 */

use App\Core\Router\Router;

Router::get(['','UserController@all']);
Router::get(['users','UserController@all']);
Router::get(['users/?','UserController@single']);
Router::get(['users/?/level/?','UserController@userLevelSingle']);
Router::post(['users','UserController@storeUser']);
Router::put(['users/?','UserController@updateLoginTime']);
Router::put(['users/?/result','UserController@updateLevelResult']);

Router::get(['levels','LevelController@all']);
Router::get(['levels/?','LevelController@single']);
Router::post(['levels','LevelController@storeNewLevel']);



