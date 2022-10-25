<?php

namespace Riclep\StoryblokForms\Traits;

use Riclep\StoryblokForms\Blocks\LsfConditionalSelect;
use Riclep\StoryblokForms\Blocks\LsfFieldset;
use Riclep\StoryblokForms\Blocks\LsfRepeatingFieldset;

trait InFieldset
{
	/**
	 * This field is in a fieldset
	 *
	 * @var bool
	 */
	protected $inFieldSet = false;


	/**
	 * This field is in a repeating fieldset
	 *
	 * @var bool
	 */
	protected $isRepeating = false;

	protected function initInFieldset(): void
	{
		if ($this->parent() instanceof LsfFieldset || $this->parent() instanceof LsfConditionalSelect) {
			$this->inFieldSet = true;
		}

		if ($this->parent() instanceof LsfRepeatingFieldset) {
			$this->isRepeating = true;
		}
	}

}