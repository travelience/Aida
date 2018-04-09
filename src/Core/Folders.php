<?php

namespace Travelience\Aida\Core;

class Folders {

    public function handle( $req, $res )
    {
        $this->createFolder( VIEWS_PATH );
        $this->createFolder( VIEWS_PATH . '/pages' );

        $this->createFolder( ASSETS_PATH );
        $this->createFolder( ASSETS_PATH . '/views' );
        $this->createFolder( ASSETS_PATH . '/cache' );
        $this->createFolder( ASSETS_PATH . '/logs' );

        $this->createFolder( CONFIG_PATH );

        $this->createFile( VIEWS_PATH . '/pages/index.blade.php', '
        <p>
        <b>Aida Framework</b>
        <hr />
        Docs: <a href="http://github.com/travelience/aida">http://github.com/travelience/aida</a>
        </p>' );
    }

    public function createFolder( $path )
    {
        if( !is_dir($path) )
        {
            @mkdir($path, 0700);
        }
    }

    public function createFile( $path, $content )
    {
        if( !file_exists($path) )
        {
            @file_put_contents($path, $content);
        }
    }

}