<?php

namespace Riclep\StoryblokForms;

use ArrayAccess;
use Riclep\StoryblokForms\Rules\ConditionallyRequired;

class Validators implements ArrayAccess
{
	/**
	 * @var array All the validation rules
	 */
	public $rules;

	/**
	 * @var Input The field the rules are applied to
	 */
	protected $field;

	/**
	 * @param $validators
	 * @param $field
	 */
	public function __construct($validators, $field)
	{
		$this->field = $field;

		$this->process($validators);
	}

	/**
	 * Get all the rules that have been added and push them into an array
	 * for Laravel’s validation
	 *
	 * @return array
	 */
	public function validationRules() {
		$rules = [];

		$hasRules = array_values($this->rules->map(function ($rule) {
			return $rule->rule();
		})->toArray());

		// We need to inject this at Validators level not Validator level so it
		// has access to all the required data as we’re referencing other fields
		// and properties
		if ($this->field->hasSettings('lsf_conditional')) {
			$hasRules[] = new ConditionallyRequired($this->field->settings('lsf_conditional'));
		}

		if ($hasRules) {
			$rules[$this->nameToValidationKey()] = $hasRules;
		}

		return $rules;
	}

	/**
	 * Get all the error messages for the rules and push them into an array
	 *
	 * @return array
	 */
	public function errorMessages() {
		$messages = [];

		$this->rules->each(function ($rule) use (&$messages) {
			if (is_object($rule->rule())) {
				$messageKey = $this->nameToValidationKey();

				$messages = array_merge($messages, [$messageKey => $rule->errorMessage()]);
			} else {
				$messageKey = $this->nameToValidationKey() . '.' . $rule->rule();

				$messages = array_merge($messages, [$messageKey => $rule->errorMessage()]);
			}
		})->toArray();

		return $messages;
	}

	/**
	 * Formats the rule’s name/key correctly for Laravel’s validator
	 *
	 * @return string
	 */
	protected function nameToValidationKey()
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
	 * Tidy up the Validator JSON from Storyblok as it contains
	 * more than we require
	 *
	 * @param $validators
	 * @return void
	 */
	protected function process($validators) {
		$this->rules = collect($validators)->map(function ($validator) {
			return (new Validator(
				array_diff_key($validator, array_flip(['_editable', '_uid']))
				, $this->field));
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