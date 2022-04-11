<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Input;
use Riclep\StoryblokForms\Validators;

class LsfCheckbox extends Input

{
	/**
	 * @var string[]
	 */
	protected $_casts = ['validators' => Validators::class];

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function checkboxes() {
		return collect(preg_split('/\r\n|\r|\n/', $this->checkboxes))->transform(function ($checkbox) {
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
}