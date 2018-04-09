<?php

namespace Travelience\Aida\Core;

abstract class Singleton {

    private static $_instances = [];

    final public static function getInstance() {
        
        $className = static::class;
        self::$_instances[$className] = self::$_instances[$className] ?? new static();

        return self::$_instances[$className];
    }

}