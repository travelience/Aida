<?php

namespace Travelience\Aida\Request;

use Illuminate\Validation\Factory;
use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Translation\Translator;

trait Validator
{
    public function validate($rules = [], $input=false)
    {
        if (!$input) {
            $input = $this->all();
        }

        $this->errors = false;
        $locale = session('lang') ?? 'en';

        $fileLoader = new FileLoader(new Filesystem, FRAMEWORK_PATH.'/config/locales/'. $locale .'/validation.php', $locale);
        $translator = new \Illuminate\Translation\Translator($fileLoader, $locale);
        $factory = new Factory($translator);

        $validator = $factory->make($input, $rules);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->getMessages();
            return false;
        }

        return true;
    }
}
