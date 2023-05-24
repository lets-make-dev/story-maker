<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}').defer }">
        <div>

            @if ($getState() !== null)
                @php
                    // Read the file's contents
                    $contents = Storage::get($getState());

                    // Encode the contents to base64
                    $audioData = base64_encode($contents);

                @endphp
                @if ($getState())
                    <audio controls>
                        <source src="data:audio/mpeg;base64,{{ $audioData }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                @endif
            @endif

        </div>

    </div>
</x-dynamic-component>

