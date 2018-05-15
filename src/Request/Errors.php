<?php

namespace Travelience\Aida\Request;

trait Errors
{
    protected $errors = [];

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function getError($field)
    {
        if (!$this->hasErrors()) {
            return false;
        }

        if ($this->hasError($field)) {
            $error = array_get($this->errors, $field);

            if (is_array($error)) {
                $error = implode(', ', $error);
            }

            return $error;
        }

        return false;
    }

    public function getErrorsAsString()
    {
        if (!$this->hasErrors()) {
            return false;
        }

        $errors = [];

        foreach ($this->errors as $error) {
            if (is_array($error)) {
                $error = implode(', ', $error);
            }

            $errors[] = $error;
        }

        return implode(', ', $errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function isValid($field)
    {
        return !$this->hasError($field);
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    public function hasError($field=false, $class=true)
    {
        $error = array_get($this->errors, $field);

        if ($error) {
            return $class;
        }

        return false;
    }
}
