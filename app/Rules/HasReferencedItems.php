<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Model\Survey;

class HasReferencedItems implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    private $message;

    private $survey_id;

    public function __construct($survey_id)
    {
        $this->survey_id = $survey_id;
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
        return !$this->hasReferences($value);
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

    private function hasReferences($array=[])
    {
        $ids =  [];
        foreach($array as $row){
            $id = (int)$row['id'];
            if( $id>0 ) $ids[] = $id; 
        }


        $survey = Survey::find($this->survey_id);

        $refs = $survey->items()->whereNotIn('id', $ids)->get();


        $this->message = __("The following records cannot be removed because they have references in another table")
        .": [ ".implode(', ', $refs->pluck('name')->toArray())." ]";

        return $refs->count()>0;
    }

}
