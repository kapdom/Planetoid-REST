<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-26
 * Time: 17:24
 */

namespace App\Core\Validator;


class Validator
{
    /**
     * Filter POST data
     *
     * @return array
     */
    public static function filterPost(): ?array
    {
        return filter_input_array(INPUT_POST, FILTER_SANITIZE_ENCODED);
    }

    /**
     * Filter string
     *
     * @param string
     * @return string
     */
    public static function filterStringData(string $value):string
    {
        return filter_var($value, FILTER_SANITIZE_ENCODED);

    }
}