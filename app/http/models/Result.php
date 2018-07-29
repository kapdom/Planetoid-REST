<?php

namespace App\Http\Models;

use App\Core\CustomInterface\IModel;

class Result extends Model implements IModel
{
    public $userId;
    public $levelId;
    public $result;
    public $fps;
    public $playDate;
    public $userName;

    public function setData($statsData)
    {
        $this->userId = $statsData['user_id'] ?? null;
        $this->levelId = $statsData['level_id'] ?? null;
        $this->result = $statsData['result'] ?? null;
        $this->fps = $statsData['fps'] ?? null;
        $this->playDate = $statsData['created_at'] ?? null;
    }
}