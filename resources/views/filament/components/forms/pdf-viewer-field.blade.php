@php
    $fileUrl = !empty($getState()) 
        ? (is_array($getState()) ? $getRoute(current($getState())) : $getRoute($getState()))
        : $getFileUrl();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <x-slot name="label">{{ $getLabel() }}</x-slot>

    <x-filament::input.wrapper>
        @if($fileUrl)
            <iframe
                src="{{ asset('vendor/filament-pdf-viewer/web/viewer.html') }}?file={{ urlencode($fileUrl) }}"
                style="width:100%; height:600px; border:1px solid #ccc;"
            ></iframe>
        @else
            <div>No PDF available</div>
        @endif
    </x-filament::input.wrapper>
</x-dynamic-component>
