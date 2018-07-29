<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-26
 * Time: 20:06
 */

namespace App\Http\db;


use App\Core\DB\DB;
use PDO;
use PDOException;
use App\Core\Services\LogService;
use App\Core\View\View;

class UserDb extends DB
{
    /**
     * Save ne user in db
     *
     * @param array $userData
     * @return int
     */
    public static function saveNewUser(array $userData):int
    {
        //check if device is already registered in db
        //if is return user id
        //otherwise create new user and return new id
        $userId = self::getUserId($userData['device_id']);
        if( $userId != 0 ){
            return $userId;
        }

        try {
            $sendData = self::$db->prepare("INSERT INTO users (id, device_id, device_model, device_os, registration_date, last_login_date)
                                        VALUES (NULL, :deviceID, :deviceModel, :deviceOS,CURRENT_TIME,CURRENT_TIME)");
            $sendData->bindValue(':deviceID', $userData['device_id'], PDO::PARAM_STR);
            $sendData->bindValue(':deviceModel', $userData['device_model'], PDO::PARAM_STR);
            $sendData->bindValue(':deviceOS', $userData['device_os'], PDO::PARAM_STR);
            $sendData->execute();
            return self::$db->lastInsertId();

        }catch (PDOException $e){
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }
    }

    /**
     * Check if user exist in db
     *
     * @param string $deviceId
     * @return int
     */
    private static function getUserId(string $deviceId):int
    {
        try {
            $getData = self::$db->prepare("SELECT id FROM users WHERE device_id=:deviceID");
            $getData->bindValue(':deviceID', $deviceId, PDO::PARAM_STR);
            $getData->execute();

            if ($getData->rowCount() > 0) {
                $data = $getData->fetch();
                return $data['id'];
            };

        }catch (PDOException $e){
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }
        return 0;
    }

    /**
     * Update user last login time
     *
     * @param int $userId
     * @return int
     */
    public static function updateUserLoginTime(int $userId):int
    {
        try {
            $updateData = self::$db->prepare("UPDATE users SET last_login_date=CURRENT_TIME WHERE id=$userId");
            $updateData->execute();
            $success = $updateData->rowCount() == 1 ? true: false;
            $updateData->closeCursor();
            unset($updateData);
            return $success;

        }catch (PDOException $e){
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }
    }

    /**
     * Update or add new level result to db
     *
     * @param int $userId
     * @param array $request
     * @return int
     */
    public static function updateLevelResult(int $userId, array $request):int
    {
        try {
            //check if result with specific user id and level id exist
            //update record if true
            //create new record if false
            if (self::checkIfResultExist($userId, $request['levelId'])) {
                $updateData = self::$db->prepare("UPDATE levels_results SET result=:result, fps=:fps, created_at=CURRENT_TIME WHERE user_id=:userId AND level_id=:levelId ");

            } else {

                $updateData = self::$db->prepare("INSERT INTO levels_results (id, user_id, level_id, result, fps) 
                                                  VALUES(NULL, :userId, :levelId, :result, :fps)");
            }

            $updateData->bindValue('userId', $userId, PDO::PARAM_INT);
            $updateData->bindValue('levelId', $request['levelId'], PDO::PARAM_INT);
            $updateData->bindValue('result', $request['result'], PDO::PARAM_STR);
            $updateData->bindValue('fps', $request['fps'], PDO::PARAM_STR);
            $updatedRecords = $updateData->execute();
            $updateData->closeCursor();
            unset($updateData);
            return $updatedRecords;

        }catch (PDOException $e){
            LogService::addLog(1,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }
    }

    /**
     * Update user last login time
     *
     * @param int $userId
     * @param int $levelId
     * @return bool
     */
    private static function checkIfResultExist(int $userId, int $levelId):bool
    {
        try {
            $selectData = self::$db->prepare("SELECT user_id FROM levels_results WHERE user_id=:userId AND level_id=:levelId");
            $selectData->bindValue('userId', $userId, PDO::PARAM_INT);
            $selectData->bindValue('levelId', $levelId, PDO::PARAM_INT);
            $selectData->execute();
            if ($selectData->rowCount() > 0) {
                return true;
            }
            return false;

        }catch(PDOException $e){
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }
    }

    public static function getSingleUserLevelResult($userId,$levelId)
    {
        try {
            $selectData = self::$db->prepare("SELECT * FROM levels_results LEFT JOIN users ON users.id=levels_results.user_id WHERE levels_results.level_id=$levelId AND levels_results.user_id=$userId");
            $selectData->bindValue('userId', $userId, PDO::PARAM_INT);
            $selectData->bindValue('levelId', $levelId, PDO::PARAM_INT);
            $selectData->execute();
            self::$fetchedData = $selectData->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }

        return self::$fetchedData;
    }
}