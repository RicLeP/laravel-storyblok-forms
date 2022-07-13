<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Str;

class BuildingMaterialsTotal implements DataAwareRule, InvokableRule
{
	/**
	 * All of the data under validation.
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Run the validation rule.
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
	 * @return void
	 */
	public function __invoke($attribute, $value, $fail)
	{
		preg_match('/^(?<baseField>.+)(\.(?<field>[^.]+)){2}$/', $attribute, $matches);

		$total = collect(data_get($this->data, $matches['baseField']))->sum($matches['field']);

		if ($total > 100) {
			$fail($this->errorMessage($total));
		}
	}

	/**
	 * @return string
	 */
	public function errorMessage($total = null)
	{
		$suffix = '';

		if ($total) {
			$suffix = ' (Currently: ' . $total . '%)';
		}

		return 'The total must add up to no more than 100%' . $suffix;
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
