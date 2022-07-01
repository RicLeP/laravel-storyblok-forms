<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\Traits\HasNames;
use Riclep\StoryblokForms\Traits\InFieldset;
use Riclep\StoryblokForms\Traits\ToJson;

class LsfFieldset extends \Riclep\Storyblok\Block
{
	use HasNames, InFieldset, ToJson;

	protected $type = 'fieldset';

	//// potentially all fields in a fieldset could be name <input name="fieldsetname[fieldname]">
	/// this would out a multidimensional array in the response.
	/// makes validation harder?


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

	public function response($input) {
		return [
			'label' => $this->label,
			'name' => $this->name,
			'response' => $this->fields->map(function ($field) use ($input) {
				//dump($field->name, $input);
				// Handle empty radio buttons etc. sending nothing in POST request
				if (!array_key_exists($field->name, $input)) {
					$input[$field->name] = null;
				}

				return $field->response($input[$field->name]);
			})->keyBy('name')->toArray(),
			'type' => $this->type,
		];
	}
}