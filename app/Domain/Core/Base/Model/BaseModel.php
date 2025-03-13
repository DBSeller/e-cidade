<?php

namespace App\Core\Base\Model;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * @param array $state
     * @return BaseModel
     */
    abstract public static function fromState(array $state);
}
