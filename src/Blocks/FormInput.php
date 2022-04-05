<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\Validators;

class FormInput extends \Riclep\Storyblok\Block
{
	protected $_casts = ['validators' => Validators::class];

	public function validationRules() {
		return $this->validators->validationRules();
	}

	public function errorMessages() {
		return $this->validators->errorMessages();
	}
}