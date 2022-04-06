<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Validators;

class FormInput extends \Riclep\Storyblok\Block
{
	protected $_casts = ['validators' => Validators::class];

	public function getNameAttribute() {
		return Str::slug($this->content()['name']);
	}

	public function validationRules() {
		return $this->validators->validationRules();
	}

	public function errorMessages() {
		return $this->validators->errorMessages();
	}

	// Interface this....
	public function response($input) {
		return $input;
	}
}