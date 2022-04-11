<?php

namespace Riclep\StoryblokForms;

class Input extends \Riclep\Storyblok\Block
{
	/**
	 * @var string[] All the Validators for this Input
	 */
	protected $_casts = ['validators' => Validators::class];

	/**
	 * All the Validation rules for this Input
	 *
	 * @return mixed
	 */
	public function validationRules() {
		return $this->validators->validationRules();
	}

	/**
	 * All the error messages for this Input
	 *
	 * @return mixed
	 */
	public function errorMessages() {
		return $this->validators->errorMessages();
	}
}