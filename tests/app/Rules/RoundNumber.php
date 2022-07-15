<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class RoundNumber implements InvokableRule
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
		// $value is a string so we need to case it but this rounds the number so check the change
		if ($value && ($value !== (string) (int) $value)) {
			$fail($this->errorMessage());
		}
    }

	/**
	 * @return string
	 */
	public function errorMessage()
	{
		return 'This must be a round number';
	}

	/**
	 * Outputs the HTML version of the validation
	 *
	 * @return string
	 */
	public function __toString()
	{
		return 'pattern="[0-9]"';
	}
}
