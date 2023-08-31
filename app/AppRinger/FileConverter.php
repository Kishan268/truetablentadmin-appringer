<?php

namespace App\AppRinger;

use Barryvdh\DomPDF\Facade\Pdf;


class FileConverter
{
    public static function convertDocToPDF($file)
    {
        $fname = now()->timestamp.'resume-convert.pdf';

        $domPdfPath = base_path('vendor/dompdf/dompdf');
        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF'); 
        $Content = \PhpOffice\PhpWord\IOFactory::load($file); 
        $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'PDF');
        $PDFWriter->save(public_path($fname)); 
        return public_path($fname);
    }
}
