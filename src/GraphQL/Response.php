<?php

namespace Travelience\Aida\GraphQL;
use Travelience\Aida\Request\Errors;

class Response
{

    use Errors;

    /**
     * @var
     */
    protected $data;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Response constructor.
     *
     * @param $data
     * @param array $errors
     */
    public function __construct($response)
    {
        if (isset($response->data)) {
            $this->data = $response->data;
        }

        if (isset($response->errors)) {
            $this->errors = $this->parseErrors( $response->errors );
        }
    }


    public function parseErrors( $errors )
    {
        $data = [];

        if( property_exists( $errors[0], 'validation' ) )
        {
            $data = (array)$errors[0]->validation;
        }

        if( property_exists( $errors[0], 'message' ) )
        {
            if(  $errors[0]->message != 'validation' )
            {
                $data['default'] = $errors[0]->message;
            }
        }

        return $data;
    }

    /**
     * Return all the data
     *
     * @return mixed
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Return the data as a JSON string
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->data);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data->{$name};
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->data->{$name} = $value;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __isset($name)
    {
        return $this->data->{$name};
    }
    
    /**
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->data->{$name});
    }
}
