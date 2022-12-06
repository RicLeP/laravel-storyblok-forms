<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\MultiInput;

class LsfSelect extends MultiInput
{
	protected string $optionsName = 'options';

	protected string $type = 'multi-input';


	/**
	 * All the error messages for this Input
	 *
	 * @return mixed
	 */
	public function errorMessages(): mixed
	{
		$messages = $this->validators->errorMessages();

		/**
		 * Rewrite required to required_if for items inside conditional selects
		 */
		if ($this->parent() instanceof LsfConditionalSelect) {
			foreach ($messages as $key => $rule) {
				if (Str::endsWith($key, 'required')) {
					$messages[$key . '_if'] = $rule;

					unset($messages[$key]);
				}
			}
		}

		return $messages;
	}
}