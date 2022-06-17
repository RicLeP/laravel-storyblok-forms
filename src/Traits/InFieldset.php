<?php

namespace Riclep\StoryblokForms\Traits;

use Riclep\StoryblokForms\Blocks\LsfConditionalSelect;
use Riclep\StoryblokForms\Blocks\LsfFieldset;
use Riclep\StoryblokForms\Blocks\LsfRepeatingFieldset;

trait InFieldset
{
	protected $inFieldSet = false;
	protected $isRepeating = false;

	protected function initInFieldset() {
		if ($this->parent() instanceof LsfFieldset || $this->parent() instanceof LsfConditionalSelect) {
			$this->inFieldSet = true;
		}

		if ($this->parent() instanceof LsfRepeatingFieldset) {
			$this->isRepeating = true;
		}
	}

}