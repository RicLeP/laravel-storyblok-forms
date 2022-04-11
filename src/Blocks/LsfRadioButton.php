<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Validators;

class LsfRadioButton extends \Riclep\Storyblok\Block
{
	/**
	 * @var string[]
	 */
	protected $_casts = ['validators' => Validators::class];

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function radioButtons() {
		return collect(explode(PHP_EOL, $this->radio_buttons))->transform(function ($radioButton) {
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
	public function response($input) {
		return $this->radioButtons()->map(function ($radioButton) use ($input) {
			if (in_array($radioButton['value'], $input)) {
				return [
					'label' => $radioButton['label'],
					'checked' => true,
				];
			}

			// TODO - store unselected radio button options in another array
		})->filter()->values()->toArray();
	}


	/**
	 * @return mixed
	 */
	public function validationRules() {
		return $this->validators->validationRules();
	}

	/**
	 * @return mixed
	 */
	public function errorMessages() {
		return $this->validators->errorMessages();
	}
}