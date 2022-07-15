<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\MultiInput;

class LsfSelect extends MultiInput
{
	/**
	 * @var string
	 */
	protected $optionsName = 'options';

	protected $type = 'multi-input';


	/**
	 * All the error messages for this Input
	 *
	 * @return mixed
	 */
	public function errorMessages() {
		$messages = $this->validators->errorMessages();

		/**
		 * Rewrite required to required_if for items inside conditional selects
		 */
		if ($this->parent() instanceof LsfConditionalSelect) {
			foreach ($messages as $key => $rule) {
				if (Str::endsWith($key, 'required')) {
					$messages[$key . '_if'] = $messages[$key];

					unset($messages[$key]);
				}
			}
		}

		return $messages;
	}
}