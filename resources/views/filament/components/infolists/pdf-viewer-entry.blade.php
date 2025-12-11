@php
    $state = $getState();
    $fileUrl = !empty($state) ? $getRoute($state) : $getFileUrl();
    $usePdfJs = $shouldUsePdfJs();
    $componentId = 'pdf-viewer-' . md5($state . time());
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="fi-sc-flex w-full">
        @if(!empty($fileUrl))
            @if($usePdfJs)
                {{-- PDF.js Viewer --}}
                <div 
                    id="{{ $componentId }}" 
                    class="pdf-viewer-container w-full border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden"
                    style="min-height: {{ $getMinHeight() }};"
                >
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

                <script type="module">
                    import * as pdfjsLib from '{{ $getPdfJsLibraryUrl() }}';
                    
                    // Set worker
                    pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ $getPdfJsWorkerUrl() }}';

                    (function() {
                        const container = document.getElementById('{{ $componentId }}');
                        const canvas = container.querySelector('.pdf-canvas');
                        const ctx = canvas.getContext('2d');
                        const pageNumDisplay = container.querySelector('.pdf-page-num');
                        const pageCountDisplay = container.querySelector('.pdf-page-count');
                        const prevBtn = container.querySelector('.pdf-prev');
                        const nextBtn = container.querySelector('.pdf-next');
                        const scaleSelect = container.querySelector('.pdf-scale');
                        
                        let pdfDoc = null;
                        let pageNum = 1;
                        let pageRendering = false;
                        let pageNumPending = null;
                        let currentScale = '{{ $getDefaultScale() }}';

                        // Load PDF
                        const loadingTask = pdfjsLib.getDocument({
                            url: '{{ $fileUrl }}',
                            @if($isBase64Data($fileUrl))
                            isEvalSupported: false,
                            @endif
                        });

                        loadingTask.promise.then(function(pdf) {
                            pdfDoc = pdf;
                            pageCountDisplay.textContent = pdf.numPages;
                            renderPage(pageNum);
                        }).catch(function(error) {
                            console.error('Error loading PDF:', error);
                            container.innerHTML = '<div class="p-4 text-red-600 dark:text-red-400">Error loading PDF: ' + error.message + '</div>';
                        });

                        function renderPage(num) {
                            pageRendering = true;
                            pdfDoc.getPage(num).then(function(page) {
                                let scale = parseFloat(currentScale);
                                const containerWidth = container.querySelector('.pdf-canvas-container').clientWidth;
                                const viewport = page.getViewport({ scale: 1 });

                                // Calculate scale based on mode
                                if (currentScale === 'auto' || currentScale === 'page-width') {
                                    scale = (containerWidth - 40) / viewport.width;
                                } else if (currentScale === 'page-fit') {
                                    const containerHeight = container.querySelector('.pdf-canvas-container').clientHeight;
                                    const widthScale = (containerWidth - 40) / viewport.width;
                                    const heightScale = (containerHeight - 40) / viewport.height;
                                    scale = Math.min(widthScale, heightScale);
                                }

                                const scaledViewport = page.getViewport({ scale: scale });
                                canvas.height = scaledViewport.height;
                                canvas.width = scaledViewport.width;

                                const renderContext = {
                                    canvasContext: ctx,
                                    viewport: scaledViewport
                                };

                                const renderTask = page.render(renderContext);
                                renderTask.promise.then(function() {
                                    pageRendering = false;
                                    if (pageNumPending !== null) {
                                        renderPage(pageNumPending);
                                        pageNumPending = null;
                                    }
                                });
                            });

                            pageNumDisplay.textContent = num;
                        }

                        function queueRenderPage(num) {
                            if (pageRendering) {
                                pageNumPending = num;
                            } else {
                                renderPage(num);
                            }
                        }

                        function onPrevPage() {
                            if (pageNum <= 1) return;
                            pageNum--;
                            queueRenderPage(pageNum);
                        }

                        function onNextPage() {
                            if (pageNum >= pdfDoc.numPages) return;
                            pageNum++;
                            queueRenderPage(pageNum);
                        }

                        function onScaleChange() {
                            currentScale = scaleSelect.value;
                            queueRenderPage(pageNum);
                        }

                        @if($shouldShowToolbar())
                        prevBtn?.addEventListener('click', onPrevPage);
                        nextBtn?.addEventListener('click', onNextPage);
                        scaleSelect?.addEventListener('change', onScaleChange);
                        
                        // Set default scale
                        if (scaleSelect) {
                            scaleSelect.value = currentScale;
                        }
                        @endif
                    })();
                </script>
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
</x-dynamic-component>
