<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Model\Survey;

class IsReferencedSurvey implements Rule
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
        $survey = Survey::find($id);

        $this->message = __('This survey cannot be deleted because it is referenced in the following evaluations')
        .": [ ". implode(',', $survey->evaluations->pluck('title')->toArray() ) . " ]";

        $isReferenced = $survey->evaluations->count()>0;

        return $isReferenced;
    }

}
