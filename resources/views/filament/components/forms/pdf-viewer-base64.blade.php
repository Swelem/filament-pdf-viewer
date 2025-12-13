@props([
    'label' => 'PDF Viewer',
    'base64' => '',
    'minHeight' => '70vh',
])

<div class="filament-field-wrapper">
    <x-slot name="label">{{ $label }}</x-slot>

    <div class="pdf-viewer-container border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden p-2" style="min-height: {{ $minHeight }};" x-data x-init="
        import('/vendor/filament-pdf-viewer/pdf.js').then(pdfjsLib => {
            pdfjsLib.GlobalWorkerOptions.workerSrc = '/vendor/filament-pdf-viewer/pdf.worker.js';

            const container = $el;
            const canvas = document.createElement('canvas');
            canvas.classList.add('pdf-canvas');
            container.appendChild(canvas);

            const loadingEl = document.createElement('div');
            loadingEl.textContent = 'Loading PDF...';
            loadingEl.className = 'pdf-loading p-2 text-center';
            container.prepend(loadingEl);

            const errorEl = document.createElement('div');
            errorEl.style.display = 'none';
            errorEl.className = 'pdf-error p-2 bg-red-100 text-red-800';
            container.prepend(errorEl);

            async function renderPdf(base64) {
                try {
                    const b64 = base64.split(',')[1];
                    const bin = atob(b64);
                    const arr = new Uint8Array(bin.length);
                    for (let i = 0; i < bin.length; i++) arr[i] = bin.charCodeAt(i);

                    const pdfDoc = await pdfjsLib.getDocument({ data: arr }).promise;

                    let currentPage = 1;
                    let scale = 1.5;

                    const renderPage = async (num) => {
                        const page = await pdfDoc.getPage(num);
                        const viewport = page.getViewport({ scale: scale });
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        await page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport }).promise;
                        loadingEl.style.display = 'none';
                    };

                    renderPage(currentPage);
                } catch (e) {
                    console.error(e);
                    errorEl.style.display = 'block';
                    errorEl.textContent = e.message;
                    loadingEl.style.display = 'none';
                }
            }

            renderPdf('{{ $base64 }}');
        });
    "></div>
</div>
