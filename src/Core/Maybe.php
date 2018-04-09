<?php

namespace Travelience\Aida\Core;

class Maybe {
    
    public $_data;

    public function __construct( $value )
    {

        if( is_object($value) && get_class( $value ) == 'Object' )
        {
            $this->_data = (object)$value->getData();
        }

        $this->_data = (object)$value;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function  __get($name) {
        if(property_exists($this->_data, $name)) {

            if( is_object($this->_data->$name) )
            {
                return new Object( $this->_data->$name );
            }

            return $this->_data->$name;
        }
        return null;
    }

    public function  __call($name, $args) {

        if(method_exists($this->_data, $name)) {

            return $this->_data->$name();
        }

        return null;
    }

    public function set( $key, $value )
    {
        return $this->_data->$key = $value;
    }

    public function  __set($name, $value) {
        $this->_data->$name = $value;
    }
}