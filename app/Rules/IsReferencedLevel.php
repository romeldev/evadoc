<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Model\Level;

class IsReferencedLevel implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    private $message;

    public function __construct()
    {

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

    private function isReferenced($id)
    {
        $isReferenced = false;

        $errors = [];
        $level = Level::find($id);


        if( $level->evaluations->count() > 0 ){
            $this->message = __('This level cannot be deleted because it is referenced in the following evaluations')
                                .": [ ". implode(',', $level->evaluations->pluck('title')->toArray() ) . " ]";
            return true;
        }

        if( $level->surveys->count() > 0 ){
            $this->message = __('This level cannot be deleted because it is referenced in the following surveys')
                                .": [ ". implode(',', $level->surveys->pluck('title')->toArray() ) . " ]";
            return true;
        }

        return false;
    }

}
