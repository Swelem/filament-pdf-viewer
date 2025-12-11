<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PDF.js Configuration
    |--------------------------------------------------------------------------
    |
    | Configure PDF.js viewer options and behavior
    |
    */

    // Use PDF.js instead of native browser viewer
    'use_pdfjs' => env('FILAMENT_PDF_VIEWER_USE_PDFJS', true),

    // PDF.js version (used for CDN fallback)
    'pdfjs_version' => '4.0.379',

    // Use CDN if local files don't exist
    'use_cdn_fallback' => true,

    // CDN URL template
    'cdn_url' => 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/{version}',

    // Default viewer options
    'viewer_options' => [
        'default_scale' => 'auto', // 'auto', 'page-fit', 'page-width', or numeric value
        'show_toolbar' => true,
        'show_page_navigation' => true,
        'enable_text_selection' => true,
        'enable_hand_tool' => true,
        'enable_annotations' => false,
    ],

    // Page rendering options
    'render_options' => [
        'scale' => 1.5,
        'enable_xfa' => true,
    ],
];
