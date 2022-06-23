<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Arr;
use Riclep\StoryblokForms\MultiInput;
use Riclep\StoryblokForms\Traits\HasNames;
use Riclep\StoryblokForms\Traits\InFieldset;
use Riclep\StoryblokForms\Traits\ToJson;

class LsfConditionalSelect extends MultiInput
{
	use HasNames, InFieldset, ToJson;

	/**
	 * @var string
	 */
	protected $optionsName = 'options';

	protected $type = 'conditional-select';




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
	/**
	 * Returns the Input’s response after the form has been submitted and validated
	 * All options are returned as an array with their name and a selected boolean
	 * based on the user’s input
	 *
	 * @param $input
	 * @return array
	 */
	public function response($input) {
		$formatted = [
			'label' => $this->label,
			'response' => ['select' => ['selected' => [], 'unselected' => []]], // TODO multi dimensional - selected and child fields...
			'type' => $this->type,
		];

		$this->options()->map(function ($formInput) use ($input, &$formatted) {
			if (in_array($formInput['value'], Arr::wrap($input))) {
				return $formatted['response']['select']['selected'][] = $formInput['label'];
			}

			return $formatted['response']['select']['unselected'][] = $formInput['label'];
		})->toArray();

		$formatted['response']['fields'] = $this->fields->map(function ($field) use ($input) {
			if (!$input) {
				return null; // TODO is this needed? When no conditional fields are added for this value?
			}

			///// no required child fields?
			// Handle empty radio buttons etc. sending nothing in POST request
			if (!array_key_exists($field->name, $input)) {
				$input[$field->name] = null;
			}

			return $field->response($input[$field->name]);
		})->toArray();

		return $formatted;
	}
}