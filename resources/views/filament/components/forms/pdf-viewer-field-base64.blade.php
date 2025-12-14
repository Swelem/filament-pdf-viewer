@php
    // Suppose $base64Pdf comes from your service or default
    // For now, just get the field's state
    $base64Pdf = $getState();
    $uniqueId = uniqid('pdf-');
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-slot name="label">{{ $getLabel() }}</x-slot>

    <x-filament::input.wrapper>
        @if($base64Pdf)
            <div 
                x-data="{ 
                    pdfData: @js($base64Pdf),
                    iframeId: '{{ $uniqueId }}',
                    messageSent: false,
                    init() {
                        if (this._initDone) return; // ✅ prevent double-init
                        this._initDone = true;

                        console.log('🟢 Alpine init - PDF length:', this.pdfData?.length);

                        const iframe = this.$refs.pdfIframe;

                        if (iframe) {
                            iframe.addEventListener('load', () => {
                                if (this.messageSent) return; // prevent duplicate send
                                console.log('🟢 Alpine: Iframe loaded');
                                setTimeout(() => {
                                    console.log('🟢 Alpine: Sending PDF...');
                                    iframe.contentWindow.postMessage({ pdfBase64: this.pdfData }, '*');
                                    this.messageSent = true;
                                }, 500);
                            });
                        }
                    }

                }"
                x-init="init()"
            >
                <iframe 
                    x-ref="pdfIframe"
                    id="{{ $uniqueId }}"
                    src="{{ asset('vendor/filament-pdf-viewer/pdfjs/web/viewer-base64.html') }}" 
                    style="width:100%; height:70vh; border: 1px solid #ccc;">
                </iframe>
            </div>
        @else
            <div>No PDF available</div>
        @endif
    </x-filament::input.wrapper>
</x-dynamic-component>
