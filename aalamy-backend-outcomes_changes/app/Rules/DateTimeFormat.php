<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DateTimeFormat implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $formatType ;
    private $dateSeparator ;
    public function __construct()
    {
        $this->formatType = config('panel.date_format').' '.config('panel.time_format');
        $this->dateSeparator = config('panel.date_separator');
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

        list($date,$time) = explode(' ',$value);

        if(is_null($date) || is_null($time))
            return  false;

        list($year,$month,$day) = explode($this->dateSeparator,$date);
        if(is_null($year) || is_null($month)||is_null($day))
            return  false;

        list($hour,$minute,$second) = explode(':',$time);
        dd($hour);
        if(is_null($hour) || is_null($minute)||is_null($second))
            return  false;

        if(strlen($year) != 4 || strlen($month) != 2 || strlen($day) != 2)
            return false;

        if(strlen($hour) != 2 || strlen($minute) != 2 || strlen($second) != 2)
            return false;

        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute '.transRuleMsg('invalid_format_date');
    }
}
