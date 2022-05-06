<?php

namespace Riclep\StoryblokForms;

use ArrayAccess;
use Illuminate\Support\Arr;

class Validators implements ArrayAccess
{
	/**
	 * @var
	 */
	public $rules;

	/**
	 * @var
	 */
	protected $field;

	/**
	 * @param $validators
	 * @param $field
	 */
	public function __construct($validators, $field)
	{
		$this->process($validators);

		$this->field = $field;
	}

	/**
	 * @return array
	 */
	public function validationRules() {
		$rules = [];

		$hasRules = array_values($this->rules->map(function ($rule) {
			return $rule->rule();
		})->toArray());

		if ($hasRules) {
			$rules[$this->nameToValidationKey()] = $hasRules;
		}

		return $rules;
	}

	/**
	 * @return array
	 */
	public function errorMessages() {
		$messages = [];

		$this->rules->each(function ($rule) use (&$messages) {
			if ($rule->errorMessage()) {
				$messageKey = $this->nameToValidationKey() . '.' . $rule->rule();

				$messages = array_merge($messages, [$messageKey => $rule->errorMessage()]);
			}
		})->toArray();

		return $messages;
	}

	/**
	 * @return array|string|string[]
	 */
	protected function nameToValidationKey(): string|array
	{
		$validationKey = str_replace([
			'[]',
			'[',
			']'
		], [
			'.*',
			'.',
			''
		], $this->field->input_name);

		return $validationKey;
	}

	/**
	 * @param $validators
	 * @return void
	 */
	protected function process($validators) {
		$this->rules = collect($validators)->transform(function ($validator) {
			return (new Validator(array_diff_key($validator, array_flip(['_editable', '_uid']))));
		});
	}

	/**
	 * @param $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->rules[$offset]);
	}

	/**
	 * @param $offset
	 * @return mixed|null
	 */
	public function offsetGet($offset)
	{
		return $this->rules[$offset] ?? null;
	}

	/**
	 * @param $offset
	 * @param $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		if (is_null($offset)) {
			$this->rules[] = $value;
		} else {
			$this->rules[$offset] = $value;
		}
	}

	/**
	 * @param $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->rules[$offset]);
	}
}