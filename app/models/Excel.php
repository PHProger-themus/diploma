<?php

namespace app\models;

use QueryBuilder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use app\models\Data;

class Excel extends QueryBuilder
{
	
	public function table()
	{
        return 'logbook';
    }
	
	public function __construct(ArrayHolder $data = null)
    {
        
    }
	
	public function saveReport($data)
	{
		$spreadsheet = new Spreadsheet();			
		$sheet = $spreadsheet->getActiveSheet();
		
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(19);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(19);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(16);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(35);
		
		$sheet->setCellValue('A1', 'Топливо');
		$sheet->setCellValue('B1', 'Стоимость');
		$sheet->setCellValue('C1', 'Дата');
		$sheet->setCellValue('D1', 'Клиент');
		$sheet->setCellValue('E1', 'Заправил');
			
		$logbook = new Data();
		$period = (isset($data->all) ? null : [$data->start, $data->end]);
		$rows = $logbook->getLogbookData(null, null, $period);
		foreach($rows as $index => $row) {
			$sheet->setCellValue('A' . ($index + 2), $row->fuel . ': ' . $row->fuel_amount . ' л.');
			$sheet->setCellValue('B' . ($index + 2), ($row->coupon ? number_format((($row->price * $row->fuel_amount) * (1 - $row->coupon / 100)), 2) : number_format(($row->price * $row->fuel_amount), 2)) . ' руб.');
			$sheet->setCellValue('C' . ($index + 2), (new \DateTime($row->date))->format('d.m.Y'));
			$sheet->setCellValue('D' . ($index + 2), $row->client);
			$sheet->setCellValue('E' . ($index + 2), $row->employee);
		}

		$writer = new Xlsx($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="file.xlsx"');
		$writer->save("php://output");
	}

}