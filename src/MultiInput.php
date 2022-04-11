<?php

namespace Riclep\StoryblokForms;

use Illuminate\Support\Str;

class MultiInput extends Input
{
	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function siblings() {
		return collect(preg_split('/\r\n|\r|\n/', $this->{$this->siblingsName}))->transform(function ($formInput) {
			if (str_starts_with($formInput, '[x]')) {
				$label = Str::after($formInput, '[x]');

				return [
					'checked' => true,
					'label' => $label,
					'value' => Str::slug($label),
				];
			}

			return [
				'checked' => false,
				'label' => $formInput,
				'value' => Str::slug($formInput),
			];
		});
	}

	/**
	 * @param $input
	 * @return array
	 */
	public function response($input) {
		return $this->siblings()->map(function ($formInput) use ($input) {
			if (in_array($formInput['value'], $input)) {
				return [
					'label' => $formInput['label'],
					'checked' => true,
				];
			}

			return [
				'label' => $formInput['label'],
				'checked' => false,
			];
		})->toArray();
	}
}