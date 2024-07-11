<?php

namespace App\Services;

use Exception;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class GeneratedDocumentService
{
    /**
     * @param string $template_source source file eg home/path/template.docx
     * @param array $options array of placeholders to replace, key value pair eg
     * ['username' => 'sammy']
     * @param string $destination destination file path eg home/path/template.pdf, 
     */
    static function handler(string $template_source, array $options, string $pathToSave){

        $directory = dirname($pathToSave);
        
        $response = [
            'status' => 'failed',
            'message' => 'failed',
        ];

        if (!file_exists($template_source)){
            $response = [
                'status' => "failed",
                'message' => "Template file doesn't exists, $template_source does not exists",
            ];
            return $response;
        }

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $templateProcessor = new TemplateProcessor($template_source);
        
        foreach ($options as $placeholder => $value) {
            $templateProcessor->setValue($placeholder, " " . $value . " ");
        }

        $filename =  uniqid(11) . '.docx';
        $fullpath = base_path($pathToSave);

        $templateProcessor->saveAs($fullpath.$filename);

        $response = [
            'status' => "successful",
            'relative_path' => $pathToSave,
            'absolute_path' => $fullpath,
            'file_name' => $filename
        ];

        return $response;
    }

}


// $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor("/Users/kehindesholadoye/GitHub/real_estate_backend/documents/realestateapp-a6af6/2023/12/02/11656b1fe90ea00.docx");

//         $templateProcessor->setValue('customer_name','Santosh');
//         $temp = public_path() . rand(1000, 100000) . ".docx";
//         $templateProcessor->saveAs($temp);

//         $phpWord = \PhpOffice\PhpWord\IOFactory::load($temp); 
//         $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');

//         $xmlWriter->save(public_path() . 'thisandthat.pdf'); 