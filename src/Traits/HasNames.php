<?php

namespace Riclep\StoryblokForms\Traits;

trait HasNames
{
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

	/**
	 * @property-read $input_dot_name
	 *
	 * @return mixed|string
	 */
	public function getInputJsonDotNameAttribute() {
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