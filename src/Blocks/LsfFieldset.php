<?php

namespace Riclep\StoryblokForms\Blocks;

class LsfFieldset extends \Riclep\Storyblok\Block
{
	//// potentially all fields in a fieldset could be name <input name="fieldsetname[fieldname]">
	/// this would out a multidimensional array in the response.
	/// makes validation herder?


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
}