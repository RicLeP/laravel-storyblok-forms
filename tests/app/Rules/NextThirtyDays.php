<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\InvokableRule;

class NextThirtyDays implements InvokableRule
{
	protected $days = 30;

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

		if ($enteredDate->lte($this->minDate()) || $enteredDate->gte($this->maxDate())) {
			$fail($this->errorMessage());
		}
    }

	/**
	 * @return string
	 */
	public function errorMessage() {
		return 'The date must be within the next ' . $this->days . ' days';
	}

	/**
	 * Outputs the HTML version of the validation
	 *
	 * @return string
	 */
	public function __toString() {
		return 'min="' . $this->minDate()->format('Y-m-d') . '" max="' . $this->maxDate()->format('Y-m-d') . '"';
	}

	/**
	 * @return \Illuminate\Support\Carbon
	 */
	private function minDate(): \Illuminate\Support\Carbon
	{
		return now();
	}

	/**
	 * @return \Illuminate\Support\Carbon
	 */
	private function maxDate(): \Illuminate\Support\Carbon
	{
		return $this->minDate()->addDays($this->days);
	}
}
