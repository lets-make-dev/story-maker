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
            {{ $getState() }}
        @endif
@endif

</div>
