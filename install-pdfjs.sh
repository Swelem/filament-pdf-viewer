#!/bin/bash

# PDF.js Installation Script
# This script downloads PDF.js files for local hosting

VERSION="4.0.379"
CDN_BASE="https://cdnjs.cloudflare.com/ajax/libs/pdf.js"
DEST_DIR="resources/dist"

echo "📦 Installing PDF.js v${VERSION}..."
echo ""

# Create destination directory
mkdir -p "$DEST_DIR"

# Download PDF.js files
echo "⬇️  Downloading pdf.min.mjs..."
curl -L "${CDN_BASE}/${VERSION}/pdf.min.mjs" -o "${DEST_DIR}/pdf.min.mjs"

echo "⬇️  Downloading pdf.worker.min.mjs..."
curl -L "${CDN_BASE}/${VERSION}/pdf.worker.min.mjs" -o "${DEST_DIR}/pdf.worker.min.mjs"

echo ""
echo "✅ PDF.js files downloaded successfully!"
echo ""
echo "📝 Next steps:"
echo "1. Publish assets: php artisan vendor:publish --tag='filament-pdf-viewer-assets'"
echo "2. The files will be copied to: public/vendor/filament-pdf-viewer/"
echo ""
echo "🎉 Installation complete!"
