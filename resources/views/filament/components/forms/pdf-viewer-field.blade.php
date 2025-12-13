@php
    use Filament\Support\Facades\FilamentView;

    $hasInlineLabel = $hasInlineLabel();
    $statePath = $getStatePath();
    $state = $getState();
    $fileUrl = !empty($state) 
        ? (is_array($state) ? $getRoute(current($state)) : $getRoute($state))
        : $getFileUrl();
    $usePdfJs = $shouldUsePdfJs();
    $componentId = 'pdf-viewer-' . md5($statePath . time());
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>
    <x-slot
        name="label"
        @class([
            'sm:pt-1.5' => $hasInlineLabel,
        ])
    >
        {{ $getLabel() }}
    </x-slot>

    <x-filament::input.wrapper
        :attributes="
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class(['fi-fo-textarea fi-sc-flex'])
        "
    >
        <div class="fi-sc-flex w-full">
            @if(!empty($fileUrl))
                @if($usePdfJs)
                    {{-- PDF.js Viewer --}}
                    @php
                        $alpineData = "{ loading: true, error: null, loadPdf() { const data = " . json_encode($fileUrl) . "; const container = this.\$el; import('https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.min.mjs').then(pdfjsLib => { pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.worker.min.mjs';";
                        
                        if($isBase64Data($fileUrl)) {
                            $alpineData .= " const b64 = data.split(',')[1]; const bin = atob(b64); const arr = new Uint8Array(bin.length); for (let i = 0; i < bin.length; i++) arr[i] = bin.charCodeAt(i); return pdfjsLib.getDocument({ data: arr }).promise;";
                        } else {
                            $alpineData .= " return pdfjsLib.getDocument({ url: data }).promise;";
                        }
                        
                        $alpineData .= " }).then(pdf => pdf.getPage(1)).then(page => { const canvas = container.querySelector('.pdf-canvas'); const vp = page.getViewport({ scale: 1.5 }); canvas.height = vp.height; canvas.width = vp.width; return page.render({ canvasContext: canvas.getContext('2d'), viewport: vp }).promise; }).then(() => { this.loading = false; }).catch(e => { console.error(e); this.error = e.message; this.loading = false; }); } }";
                    @endphp
                    <div 
                        id="{{ $componentId }}" 
                        class="pdf-viewer-container w-full border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden"
                        style="min-height: {{ $getMinHeight() }};"
                        x-data="{{ $alpineData }}"
                        x-init="setTimeout(() => loadPdf(), 500)"
                    >
                        <div x-show="loading" class="p-4 text-center">Loading PDF...</div>
                        <div x-show="error" class="p-4 bg-red-100 text-red-800">
                            Error: <span x-text="error"></span>
                        </div>
                        
                        <div class="pdf-content-wrapper overflow-auto">
                            <canvas class="pdf-canvas mx-auto"></canvas>
                        </div>
                    </div>
                @else
                    {{-- Native iframe viewer --}}
                    <iframe
                        class="fi-growable w-full"
                        src="{{ $fileUrl }}" 
                        style="min-height: {{ $getMinHeight() }};"
                    ></iframe>
                @endif
            @endif
        </div>
    </x-filament::input.wrapper>
</x-dynamic-component>
