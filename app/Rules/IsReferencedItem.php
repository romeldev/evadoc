<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class IsReferencedItem implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

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
        return !$this->isReferenced($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    private function isReferenced($value)
    {
        // if( !$value ) return false;
        $this->message = __("The item is referenced");
        return true;
    }

}
