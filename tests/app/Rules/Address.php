<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Arr;

class Address implements DataAwareRule, InvokableRule
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
		if (!$value) {
			$fail($this->errorMessage());

			return;
		}

		$address = data_get($this->data, $attribute);

		if (!array_key_exists('line1', $address) || !array_key_exists('line2', $address) || !array_key_exists('town', $address) || !array_key_exists('postcode', $address)) {
			$fail($this->errorMessage());
		}
	}

	/**
	 * @return string
	 */
	public function errorMessage()
	{
		return 'Address line 1, line 2, town and postcode are required.';
	}

	/**
	 * Outputs the HTML version of the validation
	 *
	 * @return string
	 */
	public function __toString()
	{
		return '';
	}

	/**
	 * Set the data under validation.
	 *
	 * @param array $data
	 * @return $this
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}
}
