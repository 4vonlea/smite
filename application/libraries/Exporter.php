<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Dompdf\Dompdf;
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
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @param string $filename
	 */
	public function setFilename($filename)
	{
		$this->filename = $filename;
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


	public function asExcel($costumType = [])
	{
		$colTitle = $this->headingColumn();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->mergeCellsByColumnAndRow(1, 1, count($colTitle), 1)
			->setCellValueByColumnAndRow(1, 1, $this->title)
			->getStyleByColumnAndRow(1, 1)->applyFromArray([
				'alignment' => ['horizontal' => 'center'],
				'font' => ['bold' => true, 'size' => 14]
			]);
		$startRow = 3;

		$row = $startRow;
		$col = 1;
		foreach ($colTitle as $title) {
			$sheet->setCellValueByColumnAndRow($col, $row, $title);
			$col++;
		}
		$sheet->getStyleByColumnAndRow(1, $row, count($colTitle), $row)->applyFromArray([
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color' => ['argb' => $this->color_heading]
			],
			'alignment' => ['horizontal' => 'center'],
			'font' => ['size' => 12]
		]);


		foreach ($this->data as $rowData) {
			$row++;
			$col = 1;
			foreach ($rowData as $key=>$value) {
				if(isset($costumType[$key])){
					switch($costumType[$key]){
						case 'asCurrency':
							$sheet->getStyleByColumnAndRow($col,$row)->getNumberFormat()->setFormatCode('#,##0.00');
							break;
					}
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $value);
				
				$col++;
			}
		}

		$sheet->getStyleByColumnAndRow(1, $startRow, count($colTitle), $row)->applyFromArray([
			'borders' => ['allBorders' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color' => ['argb' => '000']]
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
					th{background-color: #".$this->color_heading.";text-align: center}
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


}
