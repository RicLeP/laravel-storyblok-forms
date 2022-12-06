<?php

namespace Riclep\StoryblokForms\Blocks;

class LsfForm extends \Riclep\Storyblok\Block
{
	/**
	 * Returns all the validation rules for the fields in this Form
	 *
	 * @return array
	 */
	public function validationRules(): array
	{
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
	public function errorMessages(): array
	{
		$errorMessages = [];

		$this->fields->each(function ($field) use (&$errorMessages) {
			$errorMessages = array_merge($errorMessages, $field->errorMessages());
		});

		return $errorMessages;
	}
}