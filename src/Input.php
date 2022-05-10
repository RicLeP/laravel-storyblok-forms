<?php

namespace Riclep\StoryblokForms;

use Riclep\StoryblokForms\Blocks\LsfFieldset;
use Riclep\StoryblokForms\Blocks\LsfRepeatingFieldset;
use Riclep\StoryblokForms\Traits\ToJson;

class Input extends \Riclep\Storyblok\Block
{
	use ToJson;

	protected $inFieldSet = false;
	protected $isRepeating = false;
	protected $key;

	/**
	 * @var string[] All the Validators for this Input
	 */
	protected $_casts = ['validators' => Validators::class];

	public function __construct($content, $parent = null)
	{
		parent::__construct($content, $parent);

		if ($this->parent() instanceof LsfFieldset) {
			$this->inFieldSet = true;
		}

		if ($this->parent() instanceof LsfRepeatingFieldset) {
			$this->isRepeating = true;
		}
	}

	/**
	 * @property-read $input_name
	 *
	 * @return mixed|string
	 */
	public function getInputNameAttribute() {
		if ($this->isRepeating) {
			return $this->parent()->input_name . '[' . $this->key . '][' . $this->content()['name'] . ']';
		}

		if ($this->inFieldSet) {
			return $this->parent()->input_name . '[' . $this->content()['name'] . ']';
		}

		return $this->content()['name'];
	}

	/**
	 * @property-read $input_dot_name
	 *
	 * @return mixed|string
	 */
	public function getInputDotNameAttribute() {
		return str_replace([
			'[]',
			'[',
			']'
		], [
			'.*',
			'.',
			''
		], $this->input_name);
	}


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