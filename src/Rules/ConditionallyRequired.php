<?php

namespace Riclep\StoryblokForms\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class ConditionallyRequired implements DataAwareRule, InvokableRule
{

	/**
	 * The condition we want to check the validity of.
	 *
	 * @var array
	 */
	protected array $conditional;

	public function __construct($conditional) {
		$this->conditional = $conditional;
	}

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param  mixed  $value
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     * @return void
     */
    public function __invoke(string $attribute, mixed $value, \Closure $fail): void
    {
	    $causationFieldValue = data_get($this->data, $this->conditional['field']);

		$condition = $this->conditional['condition'];
		$operator = $this->conditional['operator'];

		$fieldIsRequired = false;

	    if ($operator === '>') {
		    $fieldIsRequired = $causationFieldValue > $condition;
	    } else if ($operator === '<') {
		    $fieldIsRequired = $causationFieldValue < $condition;
	    } else if ($operator === '==') {
		    $fieldIsRequired = $causationFieldValue == $condition;
	    }  else if ($operator === '===') {
		    $fieldIsRequired = $causationFieldValue === $condition;
	    } else if ($operator === '!=') {
		    $fieldIsRequired = $causationFieldValue != $condition;
	    }  else if ($operator === '!==') {
		    $fieldIsRequired = $causationFieldValue !== $condition;
	    } else if ($operator === '>=') {
		    $fieldIsRequired = $causationFieldValue >= $condition;
	    } else if ($operator === '<=') {
		    $fieldIsRequired = $causationFieldValue <= $condition;
	    }

	    if ($fieldIsRequired && !$value) {
		    $fail('This field is required.');
	    }
    }

	/**
	 * Outputs the HTML version of the validation
	 *
	 * @return string
	 */
	public function __toString()
	{
		return '';
	}

	/**
	 * Set the data under validation.
	 *
	 * @param array $data
	 * @return $this
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}
}
