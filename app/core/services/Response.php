<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-27
 * Time: 16:45
 */

namespace App\Core\Services;


class Response
{
    /**
     * @var string return data model namespace
     */
    protected static $modelNamespace = 'App\Http\Models';

    /**
     * @var array return data models collection
     */
    protected static $dataCollection = [];

    /**
     * Create return data as objects
     *
     * @param array $data
     * @param string $className
     * @param bool $json
     * @return array|string
     */
    public static function createObjectsCollectionToDisplay(array $data, string $className, bool $json=false)
    {
        $class = self::$modelNamespace.'\\'.$className;
        $model = new $class();

        foreach ($data as $d) {
            $newModel = clone $model;
            $newModel->setData($d);

            array_push(self::$dataCollection, $newModel);
        }
        if($json){
            return self::encodeDataToJson();
        }else{
            return self::$dataCollection;
        }
    }

    public static function encodeDataAsJson(array $data)
    {
        self::$dataCollection = $data;
        return json_encode(self::$dataCollection);
    }
    /**
     * Encode array collection to JSON format
     *
     * @return string
     */
    protected static function encodeDataToJson(): string
    {
        return json_encode(self::$dataCollection);
    }
}