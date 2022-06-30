<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Input;

class LsfAddress extends Input
{
	// Interface this....

	protected $type = 'address';

	/**
	 * All the Validation rules for this Input
	 *
	 * @return mixed
	 */
	public function validationRules() {
		$rules = $this->validators->validationRules();

		/**
		 * Rewrite required to required_if for items inside conditional selects
		 */
		if ($this->parent() instanceof LsfConditionalSelect) {
			if (is_array($this->settings('lsf_is_conditional')['when_parent_is'])) {
				$requiredWhen = implode(',', $this->settings('lsf_is_conditional')['when_parent_is']);
			} else {
				$requiredWhen = $this->settings('lsf_is_conditional')['when_parent_is'];
			}

			foreach ($rules as $key => $rule) {
				if (in_array('required', $rule)) {
					$requiredKey = array_search('required', $rule);

					$rules[$key][$requiredKey] = 'required_if:' . $this->parent()->input_dot_name . '.selected,' . $requiredWhen;
				}
			}
		}

		return $rules;
	}

	/**
	 * All the Validation rules for this Input
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

	/**
	 * Returns the Input’s response after the form has been submitted and validated
	 *
	 * @param $input
	 * @return mixed
	 */
	public function response($input) {
		return [
			'label' => $this->label,
			'name' => $this->name,
			'response' => $input,
			'type' => $this->type,
		];
	}
}