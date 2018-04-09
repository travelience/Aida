<?php 

namespace Travelience\Aida\Request;

use Hazzard\Validation\Validator as BaseValidator;

trait Validator {

    public function validate( $rules=[], $input=false )
    {
        if( !$input )
        {
            $input = $this->all();
        }

        $this->errors = false;
        $locale = session('lang') ?? 'en';

        $validator = new BaseValidator;
        $validator->setLines( require FRAMEWORK_PATH.'/config/locales/'. $locale .'/validation.php' );

        $validator = $validator->make($input, $rules);

        if ($validator->fails()) {
            
            $this->errors = $validator->errors()->getMessages();

            return false;
        }

        return true;
    }

}