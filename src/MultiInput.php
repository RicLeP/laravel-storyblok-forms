<?php

namespace Riclep\StoryblokForms;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MultiInput extends Input
{
	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function options() {
		return collect(preg_split('/\r\n|\r|\n/', $this->{$this->optionsName}))->map(function ($formInput) {
			if (str_starts_with($formInput, '[x]')) {
				$label = Str::after($formInput, '[x]');

				$selected = true;

				if (request()->session()->has('_old_input')) {
					$selected = $this->optionIsSelected($label);
				}

				return [
					'selected' => $selected,
					'label' => $label,
					'value' => Str::slug($label),
				];
			}

			return [
				'selected' => $this->optionIsSelected($formInput),
				'label' => $formInput,
				'value' => Str::slug($formInput),
			];
		});
	}

	protected function optionIsSelected($formInput) {
		if (request()->old($this->input_name) && (in_array(Str::slug($formInput), Arr::wrap(request()->old($this->input_name))))) {
			return true; // we have old input and it does include this item
		}

		return false;
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
			'response' => ['selected' => [], 'unselected' => []]
		];

		$this->options()->map(function ($formInput) use ($input, &$formatted) {
			if (in_array($formInput['value'], Arr::wrap($input))) {
				return $formatted['response']['selected'][] = $formInput['label'];
			}

			return $formatted['response']['unselected'][] = $formInput['label'];
		})->toArray();

		return $formatted;
	}
}