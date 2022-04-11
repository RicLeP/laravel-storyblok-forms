<?php

namespace Riclep\StoryblokForms\Blocks;

class FormFieldset extends \Riclep\Storyblok\Block
{
	//// potentially all fields in a fieldset could be name <input name="fieldsetname[fieldname]">
	/// this would out a multidimensional array in the response.
	/// makes validation herder?


	/**
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