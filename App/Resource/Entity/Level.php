<?php

namespace App\Resource\Entity;

use App\Core\CustomInterface\IModel;

class Level  implements IModel
{
    public $levelId;
    public $oneStarTime;
    public $twoStarTime;
    public $treeStarTime;

    /**
     * @param array $levelData
     */
    public function setData(array $levelData)
    {
        $this->levelId = $levelData['id'] ?? null;
        $this->oneStarTime = $levelData['one_star'] ?? null;
        $this->twoStarTime = $levelData['two_star'] ?? null;
        $this->treeStarTime = $levelData['three_star'] ?? null;
    }
}