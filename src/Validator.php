<?php

namespace Riclep\StoryblokForms;

use Illuminate\Support\Str;

class Validator
{
	/**
	 * @var
	 */
	protected $settings;


	protected $field;

	/**
	 * @param $settings
	 */
	public function __construct($settings, $field)
	{
		$this->settings = $settings;
		$this->field = $field;
	}

	/**
	 * @return string
	 */
	public function rule() {
		// TODO - custom string...
		// TODO - custom class - bespoke validator class?
		// TODO or use https://github.com/square/laravel-hyrule ?
		if ($this->settings['component'] === 'lsf-validator-class') {
			$class = 'App\Rules\\' . $this->settings['class'];
			return new $class;
		}


		// single parameter validators
		if (array_key_exists('parameter', $this->settings)) {
			return Str::afterLast($this->settings['component'], '-') . ':' . $this->settings['parameter'];
		}

		return Str::afterLast($this->settings['component'], '-');
	}

	/**
	 * @return mixed
	 */
	public function errorMessage() {
		if (array_key_exists('error_message', $this->settings)) {
			return $this->settings['error_message'];
		}

		if (trans()->has('validation.' . $this->rule())) {
			return __('validation.' . $this->rule(), ['attribute' => $this->field->label]);
		}

		return [];
	}
}