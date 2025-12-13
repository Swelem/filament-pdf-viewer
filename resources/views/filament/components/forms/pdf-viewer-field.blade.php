@php
    $hasInlineLabel = $hasInlineLabel();
    $statePath = $getStatePath();
    $state = $getState();
    $fileUrl = !empty($state) 
        ? (is_array($state) ? $getRoute(current($state)) : $getRoute($state))
        : $getFileUrl();
    $componentId = 'pdf-viewer-' . md5($statePath . time());
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>
    <x-slot name="label" @class(['sm:pt-1.5' => $hasInlineLabel])>
        {{ $getLabel() }}
    </x-slot>

    <x-filament::input.wrapper
        :attributes="\Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())->class(['fi-fo-textarea fi-sc-flex'])"
    >
        <div class="fi-sc-flex w-full">
            @if(!empty($fileUrl))
                <div 
                    id="{{ $componentId }}" 
                    class="pdf-viewer-container w-full border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden p-2"
                    style="min-height: {{ $getMinHeight() }};"
                    x-data
                    x-init="
                        const container = $el;
                        const canvas = container.querySelector('.pdf-canvas');
                        const loadingEl = container.querySelector('.pdf-loading');
                        const errorEl = container.querySelector('.pdf-error');
                        const pageNumEl = container.querySelector('.pdf-current-page');
                        const totalPagesEl = container.querySelector('.pdf-total-pages');

                        import('/vendor/filament-pdf-viewer/pdf.js').then(pdfjsLib => {
                            pdfjsLib.GlobalWorkerOptions.workerSrc = '/vendor/filament-pdf-viewer/pdf.worker.js';
                            
                            const data = '{{ $fileUrl }}';
                            let pdfDoc = null;
                            let currentPage = 1;
                            let scale = 1.5;

                            const renderPage = async (num) => {
                                loadingEl.style.display = 'block';
                                const page = await pdfDoc.getPage(num);
                                const viewport = page.getViewport({ scale: scale });
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                await page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport }).promise;
                                pageNumEl.textContent = num;
                                loadingEl.style.display = 'none';
                            };

                            const loadPdf = async () => {
                                try {
                                    if ({{ $isBase64Data($fileUrl) ? 'true' : 'false' }}) {
                                        const b64 = data.split(',')[1];
                                        const bin = atob(b64);
                                        const arr = new Uint8Array(bin.length);
                                        for (let i = 0; i < bin.length; i++) arr[i] = bin.charCodeAt(i);
                                        pdfDoc = await pdfjsLib.getDocument({ data: arr }).promise;
                                    } else {
                                        pdfDoc = await pdfjsLib.getDocument({ url: data }).promise;
                                    }
                                    totalPagesEl.textContent = pdfDoc.numPages;
                                    renderPage(currentPage);
                                } catch (e) {
                                    console.error(e);
                                    errorEl.style.display = 'block';
                                    errorEl.textContent = e.message;
                                }
                            };

                            loadPdf();

                            // Attach buttons
                            container.querySelector('.pdf-prev').addEventListener('click', () => {
                                if(currentPage <= 1) return;
                                currentPage--;
                                renderPage(currentPage);
                            });
                            container.querySelector('.pdf-next').addEventListener('click', () => {
                                if(currentPage >= pdfDoc.numPages) return;
                                currentPage++;
                                renderPage(currentPage);
                            });
                            container.querySelector('.pdf-zoom-in').addEventListener('click', () => {
                                scale += 0.25;
                                renderPage(currentPage);
                            });
                            container.querySelector('.pdf-zoom-out').addEventListener('click', () => {
                                scale = Math.max(0.25, scale - 0.25);
                                renderPage(currentPage);
                            });
                            container.querySelector('.pdf-download').addEventListener('click', () => {
                                const link = document.createElement('a');
                                link.href = data;
                                link.download = 'document.pdf';
                                link.click();
                            });
                            container.querySelector('.pdf-print').addEventListener('click', () => {
                                const iframe = document.createElement('iframe');
                                iframe.style.position = 'absolute';
                                iframe.style.width = '0';
                                iframe.style.height = '0';
                                iframe.src = data;
                                document.body.appendChild(iframe);
                                iframe.contentWindow.focus();
                                iframe.contentWindow.print();
                                document.body.removeChild(iframe);
                            });

                        });
                    "
                >
                    <div class="flex justify-between mb-2">
                        <div>
                            <button type="button" class="pdf-prev px-2 py-1 border rounded">Prev</button>
                            <button type="button" class="pdf-next px-2 py-1 border rounded">Next</button>
                        </div>
                        <div>
                            <button type="button" class="pdf-zoom-in px-2 py-1 border rounded">Zoom +</button>
                            <button type="button" class="pdf-zoom-out px-2 py-1 border rounded">Zoom -</button>
                            <button type="button" class="pdf-download px-2 py-1 border rounded">Download</button>
                            <button type="button" class="pdf-print px-2 py-1 border rounded">Print</button>
                        </div>
                        <div>Page <span class="pdf-current-page">1</span> of <span class="pdf-total-pages">?</span></div>
                    </div>
                    <div class="pdf-loading p-4 text-center">Loading PDF...</div>
                    <div class="pdf-error p-4 bg-red-100 text-red-800" style="display:none;"></div>
                    <canvas class="pdf-canvas mx-auto border border-gray-300 rounded-lg"></canvas>
                </div>
            @endif
        </div>
    </x-filament::input.wrapper>
</x-dynamic-component>
