<?php

namespace Riclep\StoryblokForms;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MultiInput extends Input
{

	/**
	 * Adds extra data to the JSON interpretation of the field
	 *
	 * @return array
	 */
	protected function addToJson(): array
	{
		$json['options'] = $this->options();

		return $json;
	}

	/**
	 * Processes the options entered in Storyblok
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function options(): \Illuminate\Support\Collection
	{
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
		})->values();
	}


	/**
	 * Checks if an option was preselected or in old input
	 *
	 * @param $formInput
	 * @return bool
	 */
	protected function optionIsSelected($formInput): bool
	{
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
	public function response($input): array
	{
		$formatted = [
			'label' => $this->label,
			'name' => $this->name,
			'response' => ['selected' => [], 'unselected' => []],
			'type' => $this->type,
		];

		$this->options()->map(function ($formInput) use ($input, &$formatted) {
			if (in_array($formInput['value'], Arr::wrap($input), true)) {
				return $formatted['response']['selected'][$formInput['value']] = $formInput['label'];
			}

			return $formatted['response']['unselected'][$formInput['value']] = $formInput['label'];
		})->toArray();

		return $formatted;
	}
}