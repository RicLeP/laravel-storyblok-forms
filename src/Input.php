<?php

namespace Riclep\StoryblokForms;

class Input extends \Riclep\Storyblok\Block
{
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