<?php
/** @var \RicLep\StoryblokForms\Blocks\LsfTextarea $block */
?>

<label>
	<span>{{ $block->label }}</span>

	<textarea name="{{ $block->name }}" placeholder="{{ $block->placeholder }}">{{ old($block->name) }}</textarea>

	@error($block->name )
		<small>{{ $message }}</small>
	@enderror
</label>