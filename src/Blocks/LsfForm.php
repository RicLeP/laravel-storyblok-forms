<?php

namespace Riclep\StoryblokForms\Blocks;

class LsfForm extends \Riclep\Storyblok\Block
{
	/**
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
	 * @return \Illuminate\Support\Collection
	 */
	public function flattenFields() {
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