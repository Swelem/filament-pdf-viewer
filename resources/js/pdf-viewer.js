// resources/js/pdf-viewer.js
import * as pdfjsLib from 'pdfjs-dist/build/pdf';
import 'pdfjs-dist/build/pdf.worker.entry.js';

export function renderPdf(container, fileUrl, useBase64 = false) {
    const loadingIndicator = container.querySelector('.pdf-loading');
    const errorIndicator = container.querySelector('.pdf-error');
    const canvas = container.querySelector('.pdf-canvas');

    if (loadingIndicator) loadingIndicator.style.display = 'block';
    if (errorIndicator) errorIndicator.style.display = 'none';

    const data = useBase64 ? Uint8Array.from(atob(fileUrl.split(',')[1]), c => c.charCodeAt(0)) : fileUrl;

    pdfjsLib.getDocument(useBase64 ? { data } : { url: data }).promise
        .then(pdf => pdf.getPage(1))
        .then(page => {
            const viewport = page.getViewport({ scale: 1.5 });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            return page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
        })
        .then(() => {
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        })
        .catch(err => {
            console.error(err);
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            if (errorIndicator) {
                errorIndicator.textContent = err.message;
                errorIndicator.style.display = 'block';
            }
        });
}
