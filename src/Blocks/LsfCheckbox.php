<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Validators;

class LsfCheckbox extends \Riclep\Storyblok\Block
{
	/**
	 * @var string[]
	 */
	protected $_casts = ['validators' => Validators::class];

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function checkboxes() {
		return collect(explode(PHP_EOL, $this->checkboxes))->transform(function ($checkbox) {
			if (str_starts_with($checkbox, '[x]')) {
				$label = Str::after($checkbox, '[x]');

				return [
					'checked' => true,
					'label' => $label,
					'value' => Str::slug($label),
				];
			}

			return [
				'checked' => false,
				'label' => $checkbox,
				'value' => Str::slug($checkbox),
			];
		});
	}

	/**
	 * @param $input
	 * @return array
	 */
	public function response($input) {
		return $this->checkboxes()->map(function ($checkbox) use ($input) {
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