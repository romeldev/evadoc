<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Model\Scale;

class ValidConfigType implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    private $type;

    private $message;

    public function __construct( $type=null )
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->type===null? false: $this->configValid($value, $this->type);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
        return 'Configuration type invalid.';
        // return trans('validation.uppercase');
    }

    private function configValid($value, $type)
    {

        if( $type == Scale::TYPE_LINKER ){
            return $this->linkerConfigValid( $value );
        }

        return false;
    }

    private function linkerConfigValid( $value )
    {
        $scales = [];

        foreach( explode("\n", $value) as $line )
        {
            $line = trim($line);
            if( $line != '')
            {
                $lineArray = explode(":", $line);
                if( count($lineArray)!=2){
                    $this->message = 'Cada linea de configuracion debe contener el formato key[numeric]: value[string]';
                    break;
                }

                if( trim($lineArray[0])==''){
                    $this->message = 'Cada linea de configuracion debe contener una clave';
                    break;
                }

                if( $this->isInteger($lineArray[0]) === false ){
                    $this->message = 'Cada clave debe ser de tipo entero';
                    break;
                }

                if( trim($lineArray[1])=='' ){
                    $this->message = 'Cada linea de configuracion debe contener un valor';
                    break;
                }

                $key = (int) $lineArray[0];
                $value = trim($lineArray[1]);

                if( array_search($key, array_keys($scales)) !== false){
                    $this->message = 'Las claves deben ser unicas';
                    break;
                }
                
                $scales[$key] = $value;
            }
        }

        // dd($scales);
        // dd($this->message);
        return $this->message=='';
    }


    public function isInteger( $value )
    {
        $value = str_replace("-", "", trim($value));
        return preg_match('/^[0-9]+$/', $value)===1;
    }
}
