<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\Traits\HasNames;
use Riclep\StoryblokForms\Traits\InFieldset;
use Riclep\StoryblokForms\Traits\ToJson;

class LsfFieldset extends \Riclep\Storyblok\Block
{
	use HasNames, InFieldset, ToJson;

	protected string $type = 'fieldset';

	//// potentially all fields in a fieldset could be name <input name="fieldsetname[fieldname]">
	/// this would out a multidimensional array in the response.
	/// makes validation harder?


	/**
	 * Returns all the validation rules for the fields in this Fieldset
	 *
	 * @return array
	 */
	public function validationRules(): array
	{
		$rules = [];

		$this->fields->filter(function($field) {
			return $field->component() !== 'lsf-text-note';
		})->each(function ($field) use (&$rules) {
			$rules = array_merge($rules, $field->validationRules());
		});

		return $rules;
	}

	/**
	 * Returns all the error messages for the fields in this Fieldset
	 *
	 * @return array
	 */
	public function errorMessages(): array
	{
		$rules = [];

		$this->fields->each(function ($field) use (&$rules) {
			$rules = array_merge($rules, $field->errorMessages());
		});

		return $rules;
	}

	/**
	 * Returns the Fieldset’s response after the form has been submitted and validated
	 *
	 * @param $input
	 * @return array
	 */
	public function response($input): array
	{
		return [
			'label' => $this->label,
			'name' => $this->name,
			'response' => $this->fields->map(function ($field) use ($input) {

				// Handle empty radio buttons etc. sending nothing in POST request
				// does allow empty $input break anything?
				if (!$input || !array_key_exists($field->name, $input)) {
					$input[$field->name] = null;
				}

				return $field->response($input[$field->name]);
			})->keyBy('name')->toArray(),
			'type' => $this->type,
		];
	}
}