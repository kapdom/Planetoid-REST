<?php

namespace App\Resource\Entity;

use App\Core\CustomInterface\IModel;

class Result implements IModel
{
    public $userId;
    public $levelId;
    public $result;
    public $fps;
    public $playDate;
    public $userName;

    /**
     * @param array $statsData
     */
    public function setData(array $statsData)
    {
        $this->userId = $statsData['user_id'] ?? null;
        $this->levelId = $statsData['level_id'] ?? null;
        $this->result = $statsData['result'] ?? null;
        $this->fps = $statsData['fps'] ?? null;
        $this->playDate = $statsData['created_at'] ?? null;
    }
}