<?php

namespace App\Services;

use Exception;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class GeneratedDocumentService
{
    /**
     * @param string $source source file eg home/path/template.docx
     * @param array $options array of placeholders to replace, key value pair eg
     * ['username' => 'sammy']
     * @param string $destination destination file path eg home/path/template.pdf, 
     */
    static function handler(string $source, array $options, string $destination)
    {
        $directory = dirname($destination);
        
        if (!file_exists($source))
            throw new Exception("Template file doesn't exists, $source does not exists", 1);

        if (file_exists($destination))
            throw new Exception("Destination file already exists, $destination file already exists", 1);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $templateProcessor = new TemplateProcessor($source);
        // $templateProcessor->setMacroOpeningChars('{#');
        // $templateProcessor->setMacroClosingChars('#}');
        // $templateProcessor->setMacroChars('#', '#');
        $templateProcessor->setValues($options);
        // $templateProcessor->setImageValue('company_logo', $options['company_logo']);
        $templateProcessor->saveAs($destination . '.docx'); // temp file
        $rendererName = Settings::PDF_RENDERER_DOMPDF;
        $rendererLibraryPath = base_path('vendor/dompdf/dompdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);

        $phpWord = IOFactory::load($destination . '.docx');
        $xmlWriter = IOFactory::createWriter($phpWord, 'PDF');
        
        $xmlWriter->save($destination);
            
        unlink($destination . '.docx'); // delete temp file
        dd($destination, $source);
        if (file_exists($destination))
            return $destination;
        return null;
    }
}


// $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor("/Users/kehindesholadoye/GitHub/real_estate_backend/documents/realestateapp-a6af6/2023/12/02/11656b1fe90ea00.docx");

//         $templateProcessor->setValue('customer_name','Santosh');
//         $temp = public_path() . rand(1000, 100000) . ".docx";
//         $templateProcessor->saveAs($temp);

//         $phpWord = \PhpOffice\PhpWord\IOFactory::load($temp); 
//         $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');

//         $xmlWriter->save(public_path() . 'thisandthat.pdf'); 