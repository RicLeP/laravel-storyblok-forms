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

	public function getInputNameAttribute() {
		if ($this->isRepeating) {
			return $this->parent()->input_name . '[' . $this->key . '][' . $this->content()['name'] . ']';
		}

		if ($this->inFieldSet) {
			return $this->parent()->input_name . '[' . $this->content()['name'] . ']';
		}

		return $this->content()['name'];
	}


	public function response($input) {
		//dd($input, $this);

		return $this->fields->map(function ($field) use ($input) {

//			dump($field, $input);
// TODO - handle radio buttons being empty
			dump($field, $input, $field->name);

			return $field->response($input[$field->name]);
			/*dd($field);

			return [
				'label' => $field->label,
				'response' => $field->response($input[$field->name] ?? ''),
			];*/
		})->toArray();
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