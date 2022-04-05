<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\Validators;

class FormCheckbox extends \Riclep\Storyblok\Block
{
	protected $_casts = ['validators' => Validators::class];

	public function checkboxes() {
		return explode(PHP_EOL, $this->checkboxes);
	}

	public function validationRules() {
		return $this->validators->validationRules();
	}

	public function errorMessages() {
		return $this->validators->errorMessages();
	}
}