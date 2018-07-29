<?php

namespace App\Http\Models;

use App\Core\CustomInterface\IModel;

class Level extends Model implements IModel
{
    public $levelId;
    public $oneStarTime;
    public $twoStarTime;
    public $treeStarTime;


    public function setData($levelData)
    {
        $this->levelId = $levelData['id'];
        $this->oneStarTime = $levelData['one_star'];
        $this->twoStarTime = $levelData['two_star'];
        $this->treeStarTime = $levelData['three_star'];
    }
}