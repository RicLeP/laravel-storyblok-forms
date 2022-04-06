<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Http\Request;

class Form extends \Riclep\Storyblok\Block
{
	public function validationRules() {
		$rules = [];

		$this->fields->each(function ($field) use (&$rules) {
			$rules = array_merge($rules, $field->validationRules());
		});

		return $rules;
	}

	public function errorMessages() {
		$errorMessages = [];

		$this->fields->each(function ($field) use (&$errorMessages) {
			$errorMessages = array_merge($errorMessages, $field->errorMessages());
		});

		return $errorMessages;
	}

	public function flattenFields() {
		$fields = [];

		$this->fields->each(function ($field) use (&$fields) {
			if ($field instanceof \Riclep\StoryblokForms\Blocks\FormFieldset) {
				$fields = array_merge($fields, $field->fields->toArray());
			} else {
				$fields = array_merge($fields, [$field]);
			}
		});

		return collect($fields);
	}

	// TODO move to new class
	public function responses(Request $request) {
		$input = $request->input();

		return $this->flattenFields()->map(function ($field) use ($input) {
			return [
				'label' => $field->label,
				'response' => $field->response($input[$field->name]),
			];
		})->toArray();
	}
}