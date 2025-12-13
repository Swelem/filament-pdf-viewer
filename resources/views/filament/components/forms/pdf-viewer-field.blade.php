@php
    $fileUrl = !empty($getState()) 
        ? (is_array($getState()) ? url($getRoute(current($getState()))) : url($getRoute($getState())))
        : url($getFileUrl());
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-slot name="label">{{ $getLabel() }}</x-slot>

    <x-filament::input.wrapper>
        @if($fileUrl)
            <iframe
                id="pdf-iframe"
                src="{{ asset('vendor/filament-pdf-viewer/pdfjs/web/viewer.html') }}?file={{ urlencode($fileUrl) }}"
                style="width:100%; height:70vh; border:1px solid #ccc;"
            ></iframe>
        @else
            <div>No PDF available</div>
        @endif
    </x-filament::input.wrapper>
</x-dynamic-component>
