<?php

namespace Riclep\StoryblokForms\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Collection;

class ConditionallyRequired implements DataAwareRule, InvokableRule
{

	/**
	 * The condition we want to check the validity of.
	 *
	 * @var array
	 */
	protected Collection $conditional;

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
    public function __invoke($attribute, $value, $fail): void
    {
		$causationFieldValue = data_get($this->data, $this->conditional['field'])[0];

	    // make sure we don’t cast null to 0
	    if (!is_null($causationFieldValue)) {
		    $causationFieldValue = (int) $causationFieldValue;
	    }

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
	    } else if ($operator === 'one_of') {
			$fieldIsRequired = in_array($causationFieldValue, $condition); // always array?
	    } else if ($operator === 'not_one_of') {
			$fieldIsRequired = !in_array($causationFieldValue, $condition); // always array?
	    }

	    if ($fieldIsRequired && !$value) {
		    $fail('This field is required');
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
