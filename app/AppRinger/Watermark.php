<?php

namespace App\AppRinger;

use setasign\Fpdi\Fpdi; 


class Watermark
{
    public static function addWatermarkToPdf($file)
    {
        $text_image = public_path('watermark-small.png');
        $pdf = new Fpdi(); 
        
        $pagecount = $pdf->setSourceFile($file); 
        // Add watermark image to PDF pages 
        for($i=1;$i<=$pagecount;$i++){ 
            $tpl = $pdf->importPage($i); 
            $size = $pdf->getTemplateSize($tpl); 
            $pdf->addPage(); 
            $pdf->useTemplate($tpl, 1, 1, $size['width'], $size['height'], TRUE); 
             
            //Put the watermark 
            $xxx_final = ($size['width']-200); 
            $yyy_final = ($size['height']-200); 
            $pdf->Image($text_image, $xxx_final, $yyy_final, 0, 0, 'png'); 
        } 

        $fname = now()->timestamp.'resume.pdf';
        $pdf->Output('F', $fname);

        return public_path($fname); 
    }
}
