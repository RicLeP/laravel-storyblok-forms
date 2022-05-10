<?php
/** @var \RicLep\StoryblokForms\Blocks\LsfRadioButton $block */
?>

<div>
	<p>{{ $block->label }}</p>


	@foreach($block->options() as $checkbox)
		<label>
			<input type="radio" name="{{ $block->name }}" value="{{ Str::slug($checkbox['value']) }}" @if ($checkbox['selected']) checked @endif> {{ $checkbox['label'] }}
		</label>
	@endforeach

	@error($block->input_dot_name)
		<small>{{ $message }}</small>
	@enderror
</div>