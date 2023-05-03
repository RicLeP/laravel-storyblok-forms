<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
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
	protected string $optionsName = 'options';

	protected string $type = 'conditional-select';

	/**
	 * Returns all the validation rules for the field
	 *
	 * @return array
	 */
	public function validationRules(): array
	{
		// TODO - loop over all children looking for required validation and update to required_if

		$rules = [];

		$this->fields->filter(function($field) {
			return $field->component() !== 'lsf-text-note';
		})->each(function ($field) use (&$rules) {
			$rules = array_merge($rules, $field->validationRules());
		});

		$fieldRules = parent::validationRules();

		// Should the Dot name always do this? Probably not as that would break children?
		$selectKey = $this->getInputDotNameAttribute()  . '.selected';

		if (array_key_exists($this->getInputDotNameAttribute(), $fieldRules)) {
			return array_merge($rules, [$selectKey => $fieldRules[$this->getInputDotNameAttribute()]]);
		}

		return $rules;
	}

	/**
	 * Returns all the error messages for the field
	 * @return array
	 */
	public function errorMessages(): array
	{
		$rules = [];
		$selectMessage = [];

		$this->fields->each(function ($field) use (&$rules) {
			$rules = array_merge($rules, $field->errorMessages());
		});

		$messages = $this->validators->errorMessages();

		if ($this->parent() instanceof LsfConditionalSelect) {
			foreach ($messages as $key => $rule) {
				if (Str::endsWith($key, 'required')) {
					$messages[$key . '_if'] = $rule;

					unset($messages[$key]);
				}
			}

			$selectKey = $this->getInputDotNameAttribute()  . '.selected.required_if';
			$selectMessage = [$selectKey => $messages[$this->getInputDotNameAttribute() . '.required_if']];
		} else {
			if (array_key_exists($this->getInputDotNameAttribute() . '.selected.required', $this->validationRules())) {
				$selectKey = $this->getInputDotNameAttribute()  . '.selected.required';
				$selectMessage = [
					$selectKey => $messages[
					$this->getInputDotNameAttribute() . '.required'
					]
				];
			}
		}

		return array_merge($rules, $selectMessage);
	}


	/**
	 * Returns the Input’s response after the form has been submitted and validated
	 * All options are returned as an array with their name and a selected boolean
	 * based on the user’s input
	 *
	 * @param $input
	 * @return array
	 */
	public function response($input): array
	{
		$formatted = [
			'label' => $this->label,
			'name' => $this->name,
			'response' => ['select' => ['selected' => [], 'unselected' => []]],
			'type' => $this->type,
		];

		$this->options()->map(function ($formInput) use ($input, &$formatted) {
			if ($input && $formInput['value'] === $input['selected']) {
				return $formatted['response']['select']['selected'][$formInput['value']] = $formInput['label'];
			}

			return $formatted['response']['select']['unselected'][$formInput['value']] = $formInput['label'];
		})->toArray();

		$formatted['response']['fields'] = $this->fields->map(function ($field) use ($input) {
			if (!$input) {
				return $field->response($input);
			}

			///// no required child fields?
			// Handle empty radio buttons etc. sending nothing in POST request
			if (!array_key_exists($field->name, $input)) {
				$input[$field->name] = null;
			}

			return $field->response($input[$field->name]);
		})->keyBy('name')->toArray();

		return $formatted;
	}
}