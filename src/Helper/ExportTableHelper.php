<?php


namespace Darkanakin41\TableBundle\Helper;


use Darkanakin41\TableBundle\Definition\AbstractTable;
use Darkanakin41\TableBundle\Definition\Field;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExportTableHelper
{
    const AVAILABLE_FORMATS = [
        "excel" => "excel",
        "openoffice" => "openoffice",
        "csv" => "csv",
    ];
    const CONTENT_SELECTION = [
        "displayed_columns" => "displayed_columns",
        "all_columns" => "all_columns",
    ];

    public static function isPhpSpreadSheetEnabled()
    {
        return class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet');
    }

    /**
     * @var AbstractTable
     */
    private $table;

    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * ExportTableHelper constructor.
     *
     * @param AbstractTable $table
     */
    public function __construct(AbstractTable $table)
    {
        $this->table = $table;
    }

    private function getFieldsToExport()
    {
        $exportForm = $this->table->getExportForm();

        switch ($exportForm->get('content')->getData()) {
            case 'displayed_columns':
                $fields = [];
                foreach($this->table->getFieldsDisplayed() as $fieldname){
                    $fields[] = $this->table->getField($fieldname);
                }
                return $fields;
            case 'all_columns':
                return $this->table->getFieldsVisibles();
        }

        return [];
    }

    public function generate()
    {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0);
        $worksheet = $spreadsheet->getActiveSheet();
        $fields = $this->getFieldsToExport();
        $this->generateHeaders($worksheet, $fields);

        return $this->fileContent($spreadsheet);
    }

    /**
     * @param Spreadsheet $worksheet
     * @param Field[]     $fields
     */
    public function generateHeaders(Worksheet $worksheet, array $fields)
    {
        foreach ($fields as $key => $field){
            $columnLabel = $this->table->getTranslator()->trans($field->getLabel());
            $currentColumn = array_search($key, array_keys($fields));

            $coordinates = Coordinate::stringFromColumnIndex($currentColumn+1);

            $worksheet->setCellValue($coordinates . "1", $columnLabel);
        }
    }

    private function getWritterType(){
        $exportForm = $this->table->getExportForm();

        switch ($exportForm->get('format')->getData()) {
            case 'csv':
                return 'Csv';
            case 'excel':
                return 'Xlsx';
            case 'openoffice':
                return 'Ods';
        }

        return [];
    }

    private function fileContent(Spreadsheet $spreadsheet){
        $writer = IOFactory::createWriter($spreadsheet, $this->getWritterType());
        $tmpfile = tempnam('/tmp', 'tessiextranet');
        $writer->save($tmpfile);

        return $tmpfile;
    }
}
