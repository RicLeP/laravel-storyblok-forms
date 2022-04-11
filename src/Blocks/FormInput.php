<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Validators;

class FormInput extends \Riclep\Storyblok\Block
{
	/**
	 * @var string[]
	 */
	protected $_casts = ['validators' => Validators::class];

	/**
	 * @return string
	 */
	public function getNameAttribute() {
		return Str::slug($this->content()['name']);
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

	// Interface this....

	/**
	 * @param $input
	 * @return mixed
	 */
	public function response($input) {
		return $input;
	}
}