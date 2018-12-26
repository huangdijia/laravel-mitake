<?php

namespace Huangdijia\Mitake\Facades;

use Illuminate\Support\Facades\Facade;
use Huangdijia\Mitake\Mitake as Accessor;

/**
 * @method static boolean send($mobile, $message)
 * @see \Huangdijia\Mitake\Mitake
 */

class Mitake extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Accessor::class;  
    }
}