<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-29
 * Time: 10:47
 */

namespace App\Http\Db;

use App\Core\DB\DB;
use PDO;
use PDOException;
use App\Core\Services\LogService;
use App\Core\View\View;

class LevelDb extends DB
{
    public static function addNewLevelData(array $levelData)
    {
        try {
            $sendData = self::$db->prepare("INSERT INTO levels (id, one_star, two_star, three_star)
                                        VALUES (NULL, :oneStar, :twoStar, :threeStar)");
            $sendData->bindValue(':oneStar', $levelData['one_star'], PDO::PARAM_STR);
            $sendData->bindValue(':twoStar', $levelData['two_star'], PDO::PARAM_STR);
            $sendData->bindValue(':threeStar', $levelData['three_star'], PDO::PARAM_STR);
            $sendData->execute();
            return self::$db->lastInsertId();

        }catch (PDOException $e){
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }
    }
}