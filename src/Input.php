<?php

namespace Riclep\StoryblokForms;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Blocks\LsfConditionalSelect;
use Riclep\StoryblokForms\Traits\HasNames;
use Riclep\StoryblokForms\Traits\InFieldset;
use Riclep\StoryblokForms\Traits\ToJson;

class Input extends \Riclep\Storyblok\Block
{
	use HasNames, InFieldset, ToJson;

	protected $key;

	/**
	 * @var string[] All the Validators for this Input
	 */
	protected $_casts = ['validators' => Validators::class];

	public function loopKey($key) {
		$this->key = $key;

		return $this;
	}


	/**
	 * All the Validation rules for this Input
	 *
	 * @return mixed
	 */
	public function validationRules() {
		$rules = $this->validators->validationRules();

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