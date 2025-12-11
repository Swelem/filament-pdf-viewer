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
                        $pdfJsUrl = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.min.mjs';
                        $pdfJsWorker = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.worker.min.mjs';
                        $alpineData = "{ loading: true, error: null, status: 'Initializing...', async loadPdf() { try { this.status = 'Finding canvas...'; const canvas = this.\$el.querySelector('.pdf-canvas'); if (!canvas) throw new Error('Canvas not found'); this.status = 'Loading PDF.js...'; const m = await import('{$pdfJsUrl}'); const lib = m.default || m; lib.GlobalWorkerOptions.workerSrc = '{$pdfJsWorker}'; this.status = 'Processing data...'; const data = " . json_encode($fileUrl) . ";";
                        
                        if($isBase64Data($fileUrl)) {
                            $alpineData .= " const b64 = data.split(',')[1]; const bin = atob(b64); const arr = new Uint8Array(bin.length); for (let i = 0; i < bin.length; i++) arr[i] = bin.charCodeAt(i); const pdf = await lib.getDocument({ data: arr }).promise;";
                        } else {
                            $alpineData .= " const pdf = await lib.getDocument({ url: data }).promise;";
                        }
                        
                        $alpineData .= " this.status = 'Rendering page...'; const page = await pdf.getPage(1); const vp = page.getViewport({ scale: 1.5 }); canvas.height = vp.height; canvas.width = vp.width; await page.render({ canvasContext: canvas.getContext('2d'), viewport: vp }).promise; const cnt = this.\$el.querySelector('.pdf-page-count'); if (cnt) cnt.textContent = pdf.numPages; this.loading = false; this.status = 'Complete!'; } catch (e) { this.error = e.message; this.loading = false; this.status = 'Failed'; } } }";
                    @endphp
                    <div 
                        id="{{ $componentId }}" 
                        class="pdf-viewer-container w-full border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden"
                        style="min-height: {{ $getMinHeight() }};"
                        x-data="{{ $alpineData }}"
                        x-init="setTimeout(() => loadPdf(), 500)"
                    >
                        <div x-show="loading" class="p-4 bg-blue-100 text-blue-800 font-bold">
                            <span x-text="status"></span>
                        </div>
                        <div x-show="error" class="p-4 bg-red-100 text-red-800 font-bold">
                            Error: <span x-text="error"></span>
                        </div>
                        
                        @if($shouldShowToolbar())
                        <div class="pdf-toolbar bg-gray-100 dark:bg-gray-800 border-b border-gray-300 dark:border-gray-600 p-2 flex items-center gap-2">
                            <button 
                                type="button"
                                class="pdf-prev px-3 py-1 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50"
                                title="Previous Page"
                            >
                                ←
                            </button>
                            <span class="pdf-page-info text-sm text-gray-700 dark:text-gray-300">
                                Page <span class="pdf-page-num">1</span> / <span class="pdf-page-count">--</span>
                            </span>
                            <button 
                                type="button"
                                class="pdf-next px-3 py-1 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50"
                                title="Next Page"
                            >
                                →
                            </button>
                            <div class="flex-1"></div>
                            <select class="pdf-scale text-sm border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-gray-300">
                                <option value="auto">Auto</option>
                                <option value="page-fit">Page Fit</option>
                                <option value="page-width">Page Width</option>
                                <option value="0.5">50%</option>
                                <option value="0.75">75%</option>
                                <option value="1">100%</option>
                                <option value="1.25">125%</option>
                                <option value="1.5">150%</option>
                                <option value="2">200%</option>
                            </select>
                        </div>
                        @endif
                        <div class="pdf-canvas-container overflow-auto bg-gray-200 dark:bg-gray-900" style="min-height: calc({{ $getMinHeight() }} - 3rem);">
                            <canvas class="pdf-canvas mx-auto block"></canvas>
                        </div>
                    </div>
                @else
                    {{-- Fallback to native iframe viewer --}}
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
