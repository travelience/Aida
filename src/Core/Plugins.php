<?php

namespace Travelience\Aida\Core;

use Travelience\Aida\Database\Database;
use Travelience\Aida\Mail\Mail;
use Travelience\Aida\Router\Router;
use Travelience\Aida\Router\DynamicRouter;
use Travelience\Aida\Core\Folders;
use Travelience\Aida\Core\View;
use Travelience\Aida\Request\Request;
use Travelience\Aida\Core\Response;
use Travelience\Aida\Core\Env;
use Travelience\Aida\Core\Config;
use Travelience\Aida\Core\Translator;
use Travelience\Aida\Core\Includes;
use Travelience\Aida\Router\LocalizationMiddleware;
use Travelience\Aida\Logs\Logs;
use Travelience\Aida\Auth\Auth;
use Travelience\Aida\Cache\Cache;
use Travelience\Aida\Seo\Seo;

trait Plugins {


    public function withEssentials()
    {        
        // setup
        $this->withFolders();

        // views
        $this->withViews();

        // router
        $this->withRouter();
        $this->withDynamicRoutes();

        // request
        $this->withREquest();

        // response
        $this->withResponse();

        // middlewares
        $this->withLocalization();
        $this->withTranslations();
        $this->withSeo();
        $this->withLogs();

        // Auth
        $this->withAuth();

        // Cache
        $this->withCache();

        // Mail
        $this->withMail();

        // Includes
        $this->withIncludes();

    }

    public function withHelpers()
    {
        require  FRAMEWORK_PATH . '/src/Helpers.php';
    }

    public function withConfig()
    {
        $this->on('config', new Env() );
        $this->on('config', new Config( $this ) );
    }

    public function withIncludes()
    {
        $this->on('init', new Includes($this));
    }

    public function withDatabase()
    {
        $this->set('db', Database::init());
    }

    public function withMail()
    {
        $this->set('mail', new Mail());
    }

    public function withRouter()
    {
        $this->set('router', Router::getInstance());
    }

    public function withDynamicRoutes()
    {
        $this->on('init', new DynamicRouter( VIEWS_PATH . '/'. PAGES_FOLDER ) );
    }

    public function withFolders()
    {
        $this->on('init', new Folders() );
    }

    public function withViews()
    {
        $this->on('init', new View() );
    }

    public function withRequest()
    {
        $this->set('req', Request::createFromGlobals());
        $this->on('init', new Request());
    }

    public function withResponse()
    {
        $this->set('res', new Response());
    }

    public function withLocalization()
    {
        $this->on('init', new LocalizationMiddleware());
    }

    public function withTranslations()
    {
        $this->on('init', new Translator());
    }

    public function withLogs()
    {
        $this->on('init', new Logs());
    }

    public function withAuth()
    {
        $this->set('auth',  new Auth());
    }

    public function withCache()
    {
        $this->set('cache', new Cache());
    }

    public function withSeo()
    {
        $this->set('seo', new Seo());
    }

    public function withFacebook( $config=false, $key='facebook' )
    {
        $this->set($key, facebook($config));
    }

    public function withGraphQL( $host, $headers=[], $key='graphql' )
    {
        $this->set($key, graphql($host, $headers));
    }

    public function withApi($host, $key='api')
    {
        $this->set($key, api($host));
    }
}