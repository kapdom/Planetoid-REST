<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-26
 * Time: 18:30
 */

namespace App\Core\DB;

use PDO;
use PDOException;
use Exception;
use App\Core\Services\LogService;
use App\Core\View\View;

class DB
{
    /**
     * @var PDO fetched data
     */
    protected static $fetchedData;

    /**
     * PDO object
     *
     * @var object
     */
    protected static $db;


    /**
     * Path to db configuration file
     *
     * @var string
     */
    private static $configFile = PATH.'../app/core/config/config.ini';

    /**
     * Create PDO object to db connection
     *
     * @throws Exception if file doesn't exist
     */
    public static function initialize()
    {
        try {
            if (!is_file(self::$configFile)) {
                throw new Exception("No File: ".self::$configFile);
            } else {
                $config = parse_ini_file(self::$configFile, true);
            }

            self::$db = new PDO($config['db']['driver'] . 'dbname=' . $config['db']['db_name'] . ';host=' . $config['db']['host'], $config['db']['user'], $config['db']['password']);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException | Exception $e) {
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Server Problem");
            die();
        }
    }

    /**
     * Get all record from DB
     * @param string $table
     * @return array
     */
    public static function getAllRecords(string $table):array
    {
        try {
            $getData = self::$db->query("SELECT * FROM $table");
            self::$fetchedData = $getData->fetchAll();

        } catch (PDOException $e) {
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }

        return self::$fetchedData;
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $value
     * @return array
     */
    public static function getSingleRecord(string $table, string $column, string $value ):array
    {
        try {
            $getData = self::$db->prepare("SELECT * FROM $table WHERE $column = :VALUE");
            $getData->bindValue(':VALUE', $value, self::getTypeOpValue($table));
            $getData->execute();
            self::$fetchedData = $getData->fetchAll();

        } catch (PDOException $e) {
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }
        return self::$fetchedData;
    }

    private static function getTypeOpValue($value)
    {
        $type = gettype($value);
        switch($type){
            case 'integer':
                return PDO::PARAM_INT;
                break;
            case 'string':
                return PDO::PARAM_STR;
                break;
            case 'boolean':
                return PDO::PARAM_BOOL;
                break;
            default :
                return PDO::PARAM_INT;
                break;
        }
    }
}