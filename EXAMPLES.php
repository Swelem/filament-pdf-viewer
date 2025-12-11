<?php

namespace App\Filament\Resources;

use App\Models\Document;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Section;
use Illuminate\Support\Facades\Storage;
use Swelem\FilamentPdfViewer\Forms\Components\PdfViewerField;
use Swelem\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;

/**
 * Example Resource demonstrating all PDF Viewer features
 * 
 * This example shows various ways to use the PDF viewer component
 * with different data sources and configurations.
 */
class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Example 1: Basic PDF viewer from database field
                PdfViewerField::make('file_path')
                    ->label('Document Preview')
                    ->minHeight('500px'),

                // Example 2: PDF viewer with custom height and no toolbar
                PdfViewerField::make('contract_pdf')
                    ->label('Contract')
                    ->minHeight('80vh')
                    ->showToolbar(false),

                // Example 3: PDF from external URL
                PdfViewerField::make('external_document')
                    ->label('External PDF')
                    ->fileUrl('https://example.com/sample.pdf')
                    ->minHeight('600px')
                    ->defaultScale('page-width'),

                // Example 4: Base64 encoded PDF
                PdfViewerField::make('pdf_base64')
                    ->label('Base64 PDF')
                    ->minHeight('500px')
                    ->usePdfJs(true),

                // Example 5: Disable PDF.js (use native browser viewer)
                PdfViewerField::make('simple_pdf')
                    ->label('Simple Viewer')
                    ->usePdfJs(false)
                    ->minHeight('400px'),

                // Example 6: Private file with temporary URL
                PdfViewerField::make('private_document')
                    ->label('Private Document')
                    ->visibility('private')
                    ->disk('s3')
                    ->minHeight('600px'),

                // Example 7: Full configuration
                PdfViewerField::make('full_config_pdf')
                    ->label('Advanced PDF Viewer')
                    ->minHeight('700px')
                    ->usePdfJs(true)
                    ->showToolbar(true)
                    ->defaultScale('auto')
                    ->pdfJsOptions([
                        'enable_text_selection' => true,
                        'enable_hand_tool' => true,
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Document Information')
                    ->schema([
                        // Example 8: Basic infolist entry
                        PdfViewerEntry::make('file_path')
                            ->label('Document')
                            ->minHeight('500px'),
                    ]),

                Section::make('Generated Invoice')
                    ->description('Dynamically generated PDF')
                    ->schema([
                        // Example 9: Generated PDF from closure
                        PdfViewerEntry::make('invoice_pdf')
                            ->label('Invoice')
                            ->formatStateUsing(function ($record) {
                                // Generate PDF on the fly
                                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                                    'invoices.template',
                                    ['data' => $record]
                                );
                                $output = $pdf->output();
                                
                                return 'data:application/pdf;base64,' . base64_encode($output);
                            })
                            ->minHeight('600px'),
                    ])
                    ->collapsible(),

                Section::make('External Document')
                    ->schema([
                        // Example 10: PDF from API
                        PdfViewerEntry::make('api_document')
                            ->label('API Document')
                            ->formatStateUsing(function ($record) {
                                if (!$record->api_document_url) {
                                    return null;
                                }
                                
                                // Fetch from API
                                $response = \Illuminate\Support\Facades\Http::get(
                                    $record->api_document_url
                                );
                                
                                if ($response->successful()) {
                                    return 'data:application/pdf;base64,' 
                                        . base64_encode($response->body());
                                }
                                
                                return null;
                            })
                            ->minHeight('700px')
                            ->defaultScale('page-fit'),
                    ])
                    ->collapsible(),

                Section::make('Multiple Views')
                    ->columns(2)
                    ->schema([
                        // Example 11: Side-by-side comparison
                        PdfViewerEntry::make('original_pdf')
                            ->label('Original')
                            ->minHeight('400px')
                            ->defaultScale('page-width'),

                        PdfViewerEntry::make('revised_pdf')
                            ->label('Revised')
                            ->minHeight('400px')
                            ->defaultScale('page-width'),
                    ]),
            ]);
    }
}
