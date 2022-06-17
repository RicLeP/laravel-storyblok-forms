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
		return collect(preg_split('/\r\n|\r|\n/', $this->{$this->optionsName}))->filter()->map(function ($option) {

			/**
			 * Parses the possible option formats into a named array
			 * name
			 * [*]name
			 * [key]name
			 * [key][*]name
			 * */
			preg_match('/(?:\[(?<value>[\w]+)\])?(?:\[(?<selected>\*)\])?(?<label>.+)/', $option, $settings);

			if ($settings['value'] === '') {
				$settings['value'] = Str::slug($settings['label']);
			}

			if (request()->session()->has('_old_input')) {
				$settings['selected'] = $this->optionIsSelected($settings['selected']);
			} else {
				$settings['selected'] = $settings['selected'] === '*' ? true : false;
			}

			return array_filter($settings, function ($key) {
				return is_string($key);
			}, ARRAY_FILTER_USE_KEY);
		});
	}

	protected function optionIsSelected($formInput) {
		return request()->old($this->input_name) && (in_array(Str::slug($formInput), Arr::wrap(request()->old($this->input_name))));
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
			'response' => ['selected' => [], 'unselected' => []],
			'type' => $this->type,
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