<?php

namespace Riclep\StoryblokForms\Blocks;

class LsfForm extends \Riclep\Storyblok\Block
{
	/**
	 * Returns all the validation rules for the fields in this Form
	 *
	 * @return array
	 */
	public function validationRules() {
		$rules = [];

		$this->fields->each(function ($field) use (&$rules) {
			$rules = array_merge($rules, $field->validationRules() ?: []);
		});

		return $rules;
	}

	/**
	 * Returns all the error messages for the fields in this Form
	 *
	 * @return array
	 */
	public function errorMessages() {
		$errorMessages = [];

		$this->fields->each(function ($field) use (&$errorMessages) {
			$errorMessages = array_merge($errorMessages, $field->errorMessages());
		});

		return $errorMessages;
	}

	/**
	 * Flattens the Fieldsets returning a collection of Fields
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function flattenFieldsets() {
		$fields = [];

		$this->fields->each(function ($field) use (&$fields) {
			if ($field instanceof LsfFieldset) {
				$fields = array_merge($fields, $field->fields->toArray());
			} else {
				$fields = array_merge($fields, [$field]);
			}
		});

		return collect($fields);
	}
}