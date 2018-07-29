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
    protected $fetchedData;

    /**
     * PDO object
     *
     * @var object
     */
    protected $db;

    /**
     * Path to db configuration file
     *
     * @var string
     */
    private $configFile = PATH.'../app/core/config/config.ini';

    /**
     * Create PDO object to db connection
     *
     * @throws Exception if file doesn't exist
     */
    public function __construct()
    {
        try {
            if (!is_file($this->configFile)) {
                throw new Exception("No File: ".$this->configFile);
            } else {
                $config = parse_ini_file($this->configFile, true);
            }

            $this->db = new PDO($config['db']['driver'] . 'dbname=' . $config['db']['db_name'] . ';host=' . $config['db']['host'], $config['db']['user'], $config['db']['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
    public function getAllRecords(string $table): array
    {
        try {
            $getData = $this->db->query("SELECT * FROM $table");
            $this->fetchedData = $getData->fetchAll();

        } catch (PDOException $e) {
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }

        return $this->fetchedData;
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $value
     * @return array
     */
    public function getSingleRecord(string $table, string $column, string $value ): array
    {
        try {
            $getData = $this->db->prepare("SELECT * FROM $table WHERE $column = :VALUE");
            $getData->bindValue(':VALUE', $value, self::getTypeOpValue($table));
            $getData->execute();
            $this->fetchedData = $getData->fetchAll();

        } catch (PDOException $e) {
            LogService::addLog(3,$e->getMessage());
            View::abort(500,"Internal Data Base Problem");
            die();
        }
        return $this->fetchedData;
    }

    private function getTypeOpValue($value): int
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