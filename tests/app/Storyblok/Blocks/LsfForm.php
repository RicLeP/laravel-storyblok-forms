<?php

namespace App\Storyblok\Blocks;

class LsfForm extends \Riclep\StoryblokForms\Blocks\LsfForm
{
	/**
	 * Returns all the validation rules for the fields in this Form
	 *
	 * @return array
	 */
	public function stepValidationRules($step) {
		return $this->fields[$step]->validationRules();
	}
}