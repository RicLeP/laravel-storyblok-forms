<?php

namespace Riclep\StoryblokForms;

use Illuminate\Support\Str;

class Validator
{
	protected $settings;

	/**
	 * @param $settings
	 */
	public function __construct($settings)
	{
		$this->settings = $settings;
	}

	public function rule() {
		// TODO - custom string...
		// TODO - custom class - bespoke validator class?

		// single parameter validators
		if (array_key_exists('param', $this->settings)) {
			return Str::afterLast($this->settings['component'], '-') . ':' . $this->settings['param'];
		}

		return Str::afterLast($this->settings['component'], '-');
	}

	public function errorMessage() {
		return $this->settings['error_message']; // TODO or default
	}
}