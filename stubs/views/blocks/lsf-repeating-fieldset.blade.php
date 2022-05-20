<?php
/** @var \RicLep\StoryblokForms\Blocks\LsfFieldset $block */
?>

<fieldset>
	<legend>{{ $block->label }}</legend>

	@foreach($block->fields as $field)
		{{ $field->loopKey(0)->render() }}
	@endforeach
</fieldset>

