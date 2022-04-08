<div style="background-color: #ccc;">
	<p>{{ $fieldset->label }}</p>


	@foreach($fieldset->radioButtons() as $checkbox)
		<label>
			<input type="radio" name="{{ $fieldset->name }}[]" value="{{ Str::slug($checkbox['value']) }}" @if ($checkbox['checked']) checked @endif> {{ $checkbox['label'] }}
		</label>
	@endforeach

</div>