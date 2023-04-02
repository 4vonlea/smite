<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Exporter
{

	protected $data = [];
	protected $title = "";
	protected $color_heading = "228B22";
	protected $filename = "";

	/**
	 * @param array $data
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @param string $filename
	 */
	public function setFilename($filename)
	{
		$this->filename = $filename;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFilename()
	{
		return ($this->filename === "" ? "export_" . date("hmdis") : $this->filename);
	}


	/**
	 * @return array
	 */
	public function headingColumn()
	{
		if (count($this->data) > 0) {
			$row = reset($this->data);
			$heading = [];
			foreach (array_keys($row) as $key) {
				$temp = str_replace("_", " ", $key);
				$heading[] = ucwords($temp);
			}
			return $heading;
		}
		return [];
	}

	public function summaryHotelAsExcel($data)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$row = 1;
		$col = 1;
		$sheet->setCellValueByColumnAndRow($col, $row, "Hotel dan Ruangan")
			->mergeCellsByColumnAndRow($col, $row, $col, $row + 1);
		$col++;
		foreach ($data['rangeDate'] as $range) {
			$tgl = $range['date'];
			$sheet->setCellValueByColumnAndRow($col, $row, $tgl)
				->setCellValueByColumnAndRow($col, $row + 1, "Waiting")
				->setCellValueByColumnAndRow($col + 1, $row + 1, "Pending")
				->setCellValueByColumnAndRow($col + 2, $row + 1, "Settlement")
				->setCellValueByColumnAndRow($col + 3, $row + 1, "Sisa Kouta")
				->mergeCellsByColumnAndRow($col, $row, $col + 3, $row);
			$col += 4;
		}
		$sheet->getStyleByColumnAndRow(1, $row, $col, 2)->applyFromArray([
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color' => ['argb' => $this->color_heading]
			],
			'alignment' => ['horizontal' => 'center'],
			'font' => ['size' => 12]
		]);

		$row = 2;
		foreach ($data['roomList'] as $room) {
			$col = 2;
			$row++;
			$sheet->setCellValueByColumnAndRow(1, $row, $room['hotel_name'] . " - " . $room['room_name']);
			foreach ($data['rangeDate'] as $range) {
				$tgl = $range['date'];
				$summary = $data['summary'][$tgl]["H" . $room['hotel_id']]["R" . $room['room_id']] ?? ['waiting' => 0, 'pending' => 0, 'settlement' => 0, 'sum' => 0];
				if ($tgl >= $room['start_date'] && $tgl <= $room['end_date']) {
					$sheet->setCellValueByColumnAndRow($col, $row, $summary['waiting'])
						->setCellValueByColumnAndRow($col + 1, $row, $summary['pending'])
						->setCellValueByColumnAndRow($col + 2, $row, $summary['settlement'])
						->setCellValueByColumnAndRow($col + 3, $row, $room['quota'] - $summary['sum']);
				} else {
					$sheet->setCellValueByColumnAndRow($col, $row, "Tidak Tersedia Pada Tanggal Ini")
						->mergeCellsByColumnAndRow($col, $row, $col + 3, $row);
				}
				$col += 4;
			}
		}
		$sheet->getStyleByColumnAndRow(1, 1, $col, $row)->applyFromArray([
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['argb' => '000']
				]
			]
		]);
		$writer = new Xlsx($spreadsheet);
		header('filename:' . $this->getFilename() . '.xlsx');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $this->getFilename() . '.xlsx"');
		$writer->save("php://output");
	}


	public function asExcel($costumType = [], $numberIndex = false)
	{
		$colTitle = $this->headingColumn();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->mergeCellsByColumnAndRow(1, 1, count($colTitle) + ($numberIndex ? 1 : 0), 1)
			->setCellValueByColumnAndRow(1, 1, $this->title)
			->getStyleByColumnAndRow(1, 1)->applyFromArray([
				'alignment' => ['horizontal' => 'center'],
				'font' => ['bold' => true, 'size' => 14]
			]);
		$startRow = 3;

		$row = $startRow;
		$col = 1;
		if ($numberIndex) {
			$sheet->setCellValueByColumnAndRow($col, $row, "No");
			$col++;
		}
		foreach ($colTitle as $title) {
			$sheet->setCellValueByColumnAndRow($col, $row, $title);
			$col++;
		}
		$sheet->getStyleByColumnAndRow(1, $row, count($colTitle) + ($numberIndex ? 1 : 0), $row)->applyFromArray([
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color' => ['argb' => $this->color_heading]
			],
			'alignment' => ['horizontal' => 'center'],
			'font' => ['size' => 12]
		]);


		foreach ($this->data as $rowIndex => $rowData) {
			$row++;
			$col = 1;
			$isValueExplicit = false;
			if ($numberIndex) {
				$sheet->setCellValueByColumnAndRow($col, $row, $rowIndex + 1);
				$col++;
			}
			foreach ($rowData as $key => $value) {
				if (isset($costumType[$key])) {
					switch ($costumType[$key]) {
						case 'asCurrency':
							$sheet->getStyleByColumnAndRow($col, $row)->getNumberFormat()->setFormatCode('#,##0.00');
							break;
						case 'asPhone':
							$isValueExplicit = true;
							$value = $this->normalizeNumber($value);
							break;
					}
				}
				if ($isValueExplicit) {
					$sheet->setCellValueExplicitByColumnAndRow($col, $row, $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				} else {
					$sheet->setCellValueByColumnAndRow($col, $row, $value);
				}
				$sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))
					->setAutoSize(true);
				$col++;
			}
		}

		$sheet->getStyleByColumnAndRow(1, $startRow, count($colTitle) + ($numberIndex ? 1 : 0), $row)->applyFromArray([
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['argb' => '000']
				]
			]
		]);

		$writer = new Xlsx($spreadsheet);
		header('filename:' . $this->getFilename() . '.xlsx');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $this->getFilename() . '.xlsx"');
		$writer->save("php://output");
	}

	public function asPDF()
	{
		$colTitle = $this->headingColumn();
		$html = "
				<style>
					table {
						border-collapse: collapse;
						width: 100%;
					}
					th{background-color: #" . $this->color_heading . ";text-align: center}
					table, th, td {
						border: 1px solid black;
					}
					th,td{
						padding: 1px 5px;
					}
				</style>
				<h3 style='text-align: center'>$this->title</h3>
				<table>
				";

		$html .= "<tr>";
		foreach ($colTitle as $title) {
			$html .= "<th>$title</th>";
		}
		$html .= "</tr>";
		foreach ($this->data as $row) {
			$html .= "<tr>";
			foreach ($row as $val) {
				$html .= "<td>$val</td>";
			}
			$html .= "</tr>";
		}

		$html .= "</table>";
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		header('filename:' . $this->getFilename() . '.pdf');
		$dompdf->stream($this->getFilename() . ".pdf");
	}

	public function asCSV()
	{
		ob_start();
		$csv = fopen("php://output", 'w');
		fputcsv($csv, array_keys(reset($this->data)));
		foreach ($this->data as $row) {
			fputcsv($csv, $row);
		}
		fclose($csv);
		header('filename:' . $this->getFilename() . '.csv');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $this->getFilename() . '.csv"');
	}

	protected function normalizeNumber($number)
	{
		$number = str_replace(["-", "+"], "\n", $number);
		if ($number != "" && $number[0] == "0") {
			$number = "62" . substr($number, 1, strlen($number));
		}
		return trim($number);
	}
}
