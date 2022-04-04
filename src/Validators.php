<?php

namespace Riclep\StoryblokForms;

use ArrayAccess;

class Validators implements ArrayAccess
{
	public $rules;
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

	public function getRules() {
		$rules[$this->field->name] = array_values($this->rules->map(function ($rule) {
			return $rule->rule();
		})->toArray());

		return $rules;
	}

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