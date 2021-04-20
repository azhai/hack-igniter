<?php
/**
 * hack-igniter
 *
 * A example project extends of CodeIgniter v3.x
 *
 * @package hack-igniter
 * @author  Ryan Liu (azhai)
 * @link    http://azhai.surge.sh/
 * @copyright   Copyright (c) 2013
 * @license http://opensource.org/licenses/MIT  MIT License
 */

defined('BASEPATH') or exit('No direct script access allowed');
$loader = load_class('Loader', 'core');
$loader->name_space('Psr\\SimpleCache', VENDPATH . 'psr/simple-cache/src');
$loader->name_space('PhpOffice', VENDPATH . 'phpoffice/phpspreadsheet/src');

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer;
use \PhpOffice\PhpSpreadsheet\Style;

/**
 * MS Excel or csv
 */
class MY_Excel
{
    //行数过多，强制使用CSV
    const TOO_MANY_ROWS = 50000;

    protected $file_name = '';
    protected $ext_name = 'xls';
    protected $excel = null;
    protected $data = [];
    protected $fields = [];
    protected $row_count = 0;

    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        $this->excel = new Spreadsheet();
    }

    public function save_csv()
    {
        $this->ext_name = 'csv';
        $filename = sprintf('%s.%s', $this->file_name, $this->ext_name);
        $writer = fopen('php://output', 'w') or die("can't open php://output");
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo chr(0xEF) . chr(0xBB) . chr(0xBF);
        return $writer;
    }

    public function save_xls()
    {
        //设置以Excel5格式(Excel97-2003工作簿)
        if ('xls' === $this->ext_name) {
            $writer = new Writer\Xls($this->excel);
        } else {
            $writer = new Writer\Xlsx($this->excel);
        }
        $filename = sprintf('%s.%s', $this->file_name, $this->ext_name);
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=1');
        $writer->save('php://output');
        return $writer;
    }

    public function get_col_right()
    {
        $col_right = '';
        $col_count = count($this->fields);
        if ($col_count > 26) {
            $col_right = chr(floor($col_count / 26) + 64);
        }
        $col_right .= chr($col_count % 26 + 64);
        return $col_right;
    }

    public function set_sheet_style(& $sheet, $head_lines = 0)
    {
        $col_right = $this->get_col_right();
        $rect = sprintf('A1:%s%d', $col_right, $this->row_count + $head_lines);
        $sheet->getStyle($rect)->getAlignment()
            ->setHorizontal(Style\Alignment::HORIZONTAL_CENTER);//水平居中
        $sheet->getStyle($rect)->getAlignment()
            ->setVertical(Style\Alignment::VERTICAL_CENTER); //垂直居中
        if ($head_lines) {
            $head = sprintf('A1:%s%d', $col_right, $head_lines);
            $sheet->getStyle($head)->applyFromArray(array(
                'font' => array('bold' => true),
                'alignment' => array(
                    'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array('style' => Style\Border::BORDER_THIN),
                ),
                'fill' => array(
                    'type' => Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array('argb' => 'FFA0A0A0'),
                    'endcolor' => array('argb' => 'FFFFFFFF'),
                ),
            ));
        }
    }

    public function add_data(array& $rows, array $col_fields = null)
    {
        if ($count = count($rows)) {
            if ($col_fields) {
                $this->fields = $col_fields;
            } elseif (empty($this->fields)) {
                $this->fields = array_keys(reset($rows));
            }
            $this->data = array_merge($this->data, $rows);
            $this->row_count += $count;
        }
    }

    public function download($filename, array $col_titles = null)
    {
        $this->file_name = $filename;
        if ($this->row_count > self::TOO_MANY_ROWS) {
            $writer = $this->save_csv();
            if ($col_titles) {
                fputcsv($writer, $col_titles);
            }
            foreach ($this->data as $row) {
                fputcsv($output, array_values($row));
            }
        } else {
            $sheet = $this->excel->getActiveSheet();
            $this->set_sheet_style($sheet, $col_titles ? 1 : 0);
            $gap = 1; //空行
            $chead = create_function('$c', 'return $c<26?chr($c+65):chr(floor($c/26)+64).chr($c%26+65);');
            if ($col_titles) {
                $gap ++;
                foreach ($col_titles as $c => $title) {
                    $sheet->setCellValue($chead($c).'1', $title);
                }
            }
            foreach ($this->data as $i => $row) {
                foreach ($this->fields as $c => $field) {
                    $value = $row[$field];
                    if (is_numeric($value)) {
                        $value = " " . $value;
                    }
                    $sheet->setCellValue($chead($c).($i + $gap), $value);
                }
            }
            $writer = $this->save_xls();
        }
        return die();
    }
}
