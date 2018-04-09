<?php

namespace Travelience\Aida\Core;

class Response {

   public function locales()
   {
       return config('app.locales');
   }

   public function alert($text, $type='success')
   {
       return alert($text, $type);
   }

   public function cookie($key, $value=false, $duration=60)
   {
        return cookie($key, $value, $duration);
   }

   public function redirect( $to, $code=302 )
   {

        if( !str_contains( $to, 'http' ) )
        {
            $to = route($to);
        }

        return redirect( $to, $code );
   }

   public function reload()
   {
       reload();
   }

   public function render( $file, $context=[] )
   {
       $app = app();

       $context['page'] = $file;
       $context['res'] = $app->context['res'];
       $context['req'] = $app->context['req'];

       $file = $this->getViewPath( $file, $app );

       return $app->template->make( $file, $context );
       
   }
   

   public function getViewPath( $file, $app )
   {
       if ($app->template->exists($file)) {
           return $file;
       }

       if( $this->template->exists( PAGES_FOLDER . '.' . $file ) )
       {
           return PAGES_FOLDER . '.' . $file;
       }

       return 'Aida::error';
   }

   public function __get($name) 
   {
       $app = app();
       if (isset($app->context[$name])) {
           return $app->context[$name];
       }

       return null;
   }


}