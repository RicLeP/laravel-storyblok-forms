<div style="background-color: #ccc;">
	<p>{{ $block->label }}</p>


	@foreach($block->checkboxes() as $checkbox)
		<label>
			<input type="checkbox" name="{{ $block->name }}[]" value="{{ Str::slug($checkbox['value']) }}" @if ($checkbox['checked']) checked @endif> {{ $checkbox['label'] }}
		</label>
	@endforeach

</div>