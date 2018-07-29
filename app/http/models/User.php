<?php

namespace App\Http\Models;

use App\Core\CustomInterface\IModel;

class User extends Model implements IModel
{
    public $id;
    public $deviceModel;
    public $deviceOs;
    public $regDate;
    public $lastLogDate;

    public function setData($userData)
    {
        $this->id = $userData['id'] ?? null;
        $this->deviceModel = $userData['device_model'] ?? null;
        $this->deviceOs = $this->cleanDeviceOs($userData['device_os'] ?? null);
        $this->regDate = $userData['registration_date'] ?? null;
        $this->lastLogDate = $userData['last_login_date'] ?? null;
    }
    /**
     * Remove unwanted characters
     *
     * @param $val string to clean
     * @return string
     */
    private function cleanDeviceOs(?string $val): ?string
    {
        if($val == null) return null;
        $text = explode('(',str_replace('/','',$val));
        return $text[0];

    }
}