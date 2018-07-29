<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-29
 * Time: 18:56
 */

namespace App\Core\Traits;

/**
 * Class EntityParser
 * @package App\Core\Traits
 */
trait EntityParser
{
    /**
     * @var string return data model namespace
     */
    protected $modelNamespace = 'App\Resource\Entity';

    /**
     * @var array return data models collection
     */
    protected $dataCollection = [];

    /**
     * Create return data as objects
     *
     * @param array $data
     * @param string $className
     * @param bool $json
     * @return array|string
     */
    public function createObjectsCollectionToDisplay(array $data, string $className, bool $json=false)
    {
        $class = $this->modelNamespace.'\\'.$className;
        $entity = new $class();

        foreach ($data as $d) {
            $newEntity = clone $entity;
            $newEntity->setData($d);

            array_push($this->dataCollection, $newEntity);
        }
        if($json){
            return self::encodeDataToJson();
        }else{
            return $this->dataCollection;
        }
    }

    /**
     * Encode array collection to JSON format
     *
     * @return string
     */
    protected function encodeDataToJson(): string
    {
        return json_encode($this->dataCollection);
    }
}