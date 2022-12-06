<?php

namespace Riclep\StoryblokForms;

use Illuminate\Support\Str;

class Validator
{
	/**
	 * @var array The definition of the rule from Storyblok
	 */
	protected array $definition;


	/**
	 * @var Input The Field on which the validation is being applied
	 */
	protected Input $field;

	/**
	 * @param $definition
	 * @param $field
	 */
	public function __construct($definition, $field)
	{
		$this->definition = $definition;
		$this->field = $field;
	}

	/**
	 * Converts the Rule from Storyblok into Laravel’s format
	 *
	 * @return mixed
	 */
	public function rule(): mixed
	{
		// If using a Class based rule return a new instant
		if ($this->definition['component'] === 'lsf-validator-class') {
			$class = 'App\Rules\\' . $this->definition['class'];

			if (array_key_exists('parameter', $this->definition) && $this->definition['parameter']) {
				return new $class($this->definition['parameter']);
			}

			return new $class;
		}

		// named rule with a parameter
		if (array_key_exists('parameter', $this->definition)) {
			if (array_key_exists('value', $this->definition)) {
				return Str::afterLast($this->definition['component'], '-') . ':' . $this->definition['parameter'] . ',' . $this->definition['value'];
			}

			return Str::afterLast($this->definition['component'], '-') . ':' . $this->definition['parameter'];
		}

		// simple text only rule lsf-validator-required
		return Str::afterLast($this->definition['component'], '-');
	}

	/**
	 * Returns the validation rule in a format for the message bag
	 * removing :somevalue etc.
	 *
	 * @return string
	 */
	public function ruleForValidation(): string
	{
		return Str::before($this->rule(), ':');
	}

	/**
	 * Get the error messages for the rule. Class validators must implement
	 * an errorMessage() method
	 *
	 * @return mixed
	 */
	public function errorMessage()
	{
		// Class validators
		if ($this->definition['component'] === 'lsf-validator-class') {
			$class = 'App\Rules\\' . $this->definition['class'];

			return;
		}

		// If an error message was defined in Storyblok
		if (array_key_exists('error_message', $this->definition) && $this->definition['error_message']) {
			return $this->definition['error_message'];
		}

		// Get the default message from Laravel
		if (trans()->has('validation.' . $this->rule())) {
			return __('validation.' . $this->rule(), ['attribute' => $this->field->label]);
		}

		return '';
	}
}