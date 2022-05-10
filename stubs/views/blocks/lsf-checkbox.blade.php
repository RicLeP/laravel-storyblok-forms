<?php
/** @var \RicLep\StoryblokForms\Blocks\LsfCheckbox $block */
?>

<div>
	<p>{{ $block->label }}</p>


	@foreach($block->options() as $checkbox)
		<label>
			<input type="checkbox" name="{{ $block->name }}[]" value="{{ Str::slug($checkbox['value']) }}" @if (old($block->input_dot_name) === Str::slug($checkbox['value']) || (!old($block->input_dot_name) && $checkbox['selected'])) checked @endif> {{ $checkbox['label'] }}
		</label>
	@endforeach

	@error($block->name )
		<small>{{ $message }}</small>
	@enderror
</div>