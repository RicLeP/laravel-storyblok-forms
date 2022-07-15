<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\InvokableRule;

class PastDate implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
	    $enteredDate = Carbon::parse($value);

	    if ($enteredDate->gte($this->maxDate())) {
		    $fail($this->errorMessage());
	    }
    }

	/**
	 * @return string
	 */
	public function errorMessage() {
		return 'The date must be in the past';
	}

	/**
	 * Outputs the HTML version of the validation
	 *
	 * @return string
	 */
	public function __toString() {
		return 'max="' . $this->maxDate()->format('Y-m-d') . '"';
	}


	/**
	 * @return \Illuminate\Support\Carbon
	 */
	private function maxDate(): \Illuminate\Support\Carbon
	{
		return now()->subDay();
	}
}
