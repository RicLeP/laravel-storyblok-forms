<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\MultiInput;
use Riclep\StoryblokForms\Validators;

class LsfRadioButton extends MultiInput
{
	/**
	 * @var string
	 */
	protected $siblingsName = 'radio_buttons';

	/**
	 * @var string[]
	 */
	protected $_casts = ['validators' => Validators::class];

	/**
	 * @return \Illuminate\Support\Collection
	 */
	/*public function radioButtons() {
		return collect(preg_split('/\r\n|\r|\n/', $this->radio_buttons))->transform(function ($radioButton) {
			if (str_starts_with($radioButton, '[x]')) {
				$label = Str::after($radioButton, '[x]');

				return [
					'checked' => true,
					'label' => $label,
					'value' => Str::slug($label),
				];
			}

			return [
				'checked' => false,
				'label' => $radioButton,
				'value' => Str::slug($radioButton),
			];
		});
	}

	/**
	 * @param $input
	 * @return array
	 */
	/*public function response($input) {
		return $this->radioButtons()->map(function ($checkbox) use ($input) {
			if (in_array($checkbox['value'], $input)) {
				return [
					'label' => $checkbox['label'],
					'checked' => true,
				];
			}

			return [
				'label' => $checkbox['label'],
				'checked' => false,
			];
		})->toArray();
	}*/
}