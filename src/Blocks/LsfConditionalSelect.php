<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\MultiInput;

class LsfConditionalSelect extends MultiInput
{
	/**
	 * @var string
	 */
	protected $optionsName = 'options';

	protected $type = 'multi-input';




	/**
	 * Returns all the validation rules for the fields in this Fieldset
	 *
	 * @return array
	 */
	public function validationRules() {
		$rules = [];

		$this->fields->each(function ($field) use (&$rules) {
			$rules = array_merge($rules, $field->validationRules());
		});

		return $rules;
	}

	/**
	 * Returns all the error messages for the fields in this Fieldset
	 *
	 * @return array
	 */
	public function errorMessages() {
		$rules = [];

		$this->fields->each(function ($field) use (&$rules) {
			$rules = array_merge($rules, $field->errorMessages());
		});

		return $rules;
	}



	// TODO - store the selected input value
	// TODO - only respond with conditionally on inputs

	public function response($input) {
		return [
			'label' => $this->label,
			'response' => $this->fields->map(function ($field) use ($input) {

				// Handle empty radio buttons etc. sending nothing in POST request
				if (!array_key_exists($field->name, $input)) {
					$input[$field->name] = null;
				}


				return $field->response($input[$field->name]);
			})->toArray(),
			'type' => $this->type,
		];
	}
}