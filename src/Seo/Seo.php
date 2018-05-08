<?php

namespace Travelience\Aida\Seo;

use Travelience\Aida\Session\Session;

class Seo
{
    public $key = 'seo';
    
    public function getKey($key)
    {
        return $this->key . '.' . $key;
    }

    public function getTitle()
    {
        $text = current_route();
        $text = explode('.', $text);
        $title = [];
        $prev = false;

        foreach( $text as $item )
        {
            
            if( substr($item,0,1) == '_' )
            {
                continue;    
            }

            $item = title_case($item);

            $title[] = $item;
            $prev = $item;
        }
        $title = implode(' › ', $title);
        return $title;
    }

    public function set($key, $value)
    {
        Session::set( $this->getKey($key), $value);
    }

    public function get($key, $value='')
    {
        return Session::get( $this->getKey($key), $value);
    }

    public function forget()
    {
        Session::forget( $this->key );
    }
    

    public function metas()
    {
        $meta = [];

        // remove last /
        $current_url = current_url();

        if (substr($current_url, -1, 1) == '/') {
            $current_url = substr($current_url, 0, -1);
        }

        $current_locale = Session::get('lang');

        // meta language
        $meta[] = "<meta http-equiv='content-language' content='". $current_locale ."'>";
        $meta[] = "<meta content='". $current_locale ."' name='language' />";
      
        // default locale
        $alternate = str_replace('/'. $current_locale .'/', '/' . config('app.locale') . '/', $current_url);
        $meta[] = "<link rel='canonical' href='". $current_url ."' />";
        $meta[] = "<link rel='alternate' hreflang='x-default' href='$alternate' />";

        // locales
        if (config('app.locales')) {
            foreach (config('app.locales') as $locale) {
                $alternate = str_replace('/'. $current_locale .'/', '/' . $locale . '/', $current_url);
                $meta[] = "<link rel='alternate' hreflang='$locale' href='$alternate' />";
            }
        }

        return implode("\r\n", $meta);
    }
}
