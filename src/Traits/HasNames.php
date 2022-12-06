<?php

namespace Riclep\StoryblokForms\Traits;

trait HasNames
{
	// TODO - these methods should be FullName not Name.... keep name untouched

	/**
	 * @property-read $input_name
	 *
	 * @return string
	 */
	public function getInputNameAttribute(): string
	{
		if ($this->isRepeating) {
			return $this->parent()->input_name . '[' . $this->key . '][' . $this->content()['name'] . ']';
		}

		if ($this->inFieldSet) {
			return $this->parent()->input_name . '[' . $this->content()['name'] . ']';
		}

		return $this->content()['name'];
	}

	/**
	 * @return string
	 *@property-read $input_dot_name
	 *
	 */
	public function getInputDotNameAttribute(): string
	{
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

	/**
	 * @property-read $input_dot_name
	 *
	 * @return string
	 */
	public function getInputJsonDotNameAttribute(): string
	{
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
}