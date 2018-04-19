<?php

namespace Travelience\Aida\Database;

use Travelience\Aida\Core\Singleton;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Pagination\Paginator;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Database extends Singleton {

    public $capsule; 

    public function __construct()
    {
        $config = $config ?? config('database');

        $capsule = new Capsule;
        $capsule->addConnection($config);
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        self::setPagination();

        $this->capsule = $capsule;
    }

    public function getBuilder()
    {
        return $this->capsule;
    }

    public static function init()
    {
        $capsule = new Database();
        return $capsule->getBuilder();
    }

    public static function setPagination()
    {

        Paginator::currentPathResolver(function () {
            return current_url();
        });

        Paginator::currentPageResolver(function ($pageName = 'page') {
            $page = $_GET[$pageName] ?? 1;

            if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
                return $page;
            }

            return 1;
        });

    }

}