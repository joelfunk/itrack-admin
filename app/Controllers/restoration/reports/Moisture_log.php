<?php namespace App\Controllers\restoration\reports;

use Interpid\PdfLib\Multicell;
use Interpid\PdfLib\Pdf;

class Moisture_log extends BaseController
{
	public function index()
	{
		helper(['pdf/inches', 'pdf/columns']);

		// Init
		$pdf = new Pdf();
		$multicell = new Multicell( $pdf );
		$pdf->multicell = $multicell;
		$pdf->orien = 'P';
		$pdf->size = 'Letter';
		$pdf->lhs = 4;
		$pdf->lh = 4.5;
		$pdf->lht = 6.5;

		
		$pdf->SetMargins(inches(.5), inches(.5), inches(.5));
		$pdf->SetDrawColor(0);
		$pdf->SetFillColor(0);
		$pdf->SetFont('Arial','',10);
		$pdf->multicell->SetStyle("s","arial","",9,"100,100,100"); 	
		$pdf->multicell->SetStyle("h1","arial","",14,"50,50,50"); 	
		$pdf->multicell->SetStyle("h2","arial","",12,"50,50,50"); 	
		$pdf->multicell->SetStyle("h3","arial","",10,"50,50,50"); 	
		$pdf->multicell->SetStyle("b","arial","B",10,"50,50,50"); 	

		$pdf->AddPage('P', 'Letter'); // 216 x 280mm
		$pdf->SetAutoPageBreak(false);

		$logo = 'https://enspiremanager-uploads.s3-us-west-2.amazonaws.com/itrack-systems/hukills/hukills-logo.png';
		$address = '3855 Credit Lake Hwy, Medford, OR 97504';
		$phones = 'Phone: (541) 734-9000 - Fax: (541) 772-0407';
		$license = 'OR License: # 49225, Fed ID: # 93-0899723';

		$insured = 'Mike Melvin';
		$insured_address1 = '112 Monterey Drive';
		$insured_address2 = 'Medford, OR 97504';

		$est_name = 'Josiah Funkhouser';
		$est_position = 'Manager/Estimator';
		$est_phone = '(541) 821-9369';
		$est_email = 'josiah@hukills.com';

		$claim_number = '2348976234-1';

		$table1 = array(
			array(' ', 'Feb 12', 'Feb 13', 'Feb 14', 'Feb 15', 'Feb 19', 'Feb 21'),
			array('Temp°', '68.9', '70.2', '72.2', '85', '86.2', '88.2'),
			array('RH %', '48.2', '41.2', '38', '29.5', '26.1', '24'),
		);

		$table2 = array(
			array(' ', 'Feb 12', 'Feb 13', 'Feb 14', 'Feb 15', 'Feb 19', 'Feb 21'),
			array('Subfloor', '44.6', '32.3', '28.3', '24', '20.4', '14.6'),
		);

		$table3 = array(
			array(' ', 'Feb 12', 'Feb 13', 'Feb 14'),
			array('Temp°', '68.6', '69.6', '71'),
			array('RH %', '42.6', '38.7', '37'),
		);

		$table4 = array(
			array(' ', 'Feb 12', 'Feb 13', 'Feb 14', 'Feb 15', 'Feb 19', 'Feb 21'),
			array('Temp°', '47', '43.9', '41', '41', '34.2', '35'),
			array('RH %', '51', '76.2', '80', '57.1', '82.2', '79'),
		);

		// Build PDF
		$y = $this->company($pdf, inches(.5), $logo, $address, $phones, $license);
		$y = $this->customer($pdf, $y, $insured, $insured_address1, $insured_address2, $claim_number);
		$y = $this->estimator($pdf, $y, $est_name, $est_position, $est_phone, $est_email);

		$y = $this->heading($pdf, $y, 'BATHROOM');
		$y = $this->sub_heading($pdf, $y, 'Ambient Conditions');
		$y = $this->table($pdf, $y, inches(.5), inches(8), $table1);
		$y = $this->sub_heading($pdf, $y, 'Structural Moisture');
		$y = $this->table($pdf, $y, inches(.5), inches(8), $table2);
		$y += 5;

		$y = $this->heading($pdf, $y, 'BEDROOM');
		$y = $this->sub_heading($pdf, $y, 'Ambient Conditions');
		$y = $this->table($pdf, $y, inches(.5), inches(8), $table3);
		$y += 5;

		$y = $this->heading($pdf, $y, 'LIVING ROOM');
		$y = $this->sub_heading($pdf, $y, 'Unaffected');
		$y = $this->table($pdf, $y, inches(.5), inches(8), $table4);
		$y += 5;

		// Output
		$pdf->SetDisplayMode('real');
		$pdf->Output('I');
		$response->setHeader('Content-type', 'application/pdf');
	}
	protected function company($pdf, $y, $logo, $address, $phones, $license)
	{
		// Logo and Line
		$pdf->Image($logo, inches(.5), $y, 90);
		$pdf->SetDrawColor(50);
		$pdf->SetLineWidth(.75);
		$pdf->Line(inches(4.25), $y + 14, inches(8), $y + 14);

		// Address
		$pdf->SetXY(inches(.5), $y+3);
		$pdf->multicell->multiCell(0, $pdf->lhs, "<s>$address\n$phones</s>", 0, "R", false);

		// License
		$pdf->SetXY(inches(.5), $y + 17);
		$pdf->multicell->multiCell(0, $pdf->lhs, "<s>$license</s>", 0, "R", false);

		return $pdf->GetY() + 8;
	}
	protected function customer($pdf, $y, $insured, $insured_address1, $insured_address2, $claim_number)
	{
		$pdf->SetXY(inches(4), $y);
		$pdf->multicell->multiCell(inches(1.25), $pdf->lh, "<h3>Claim Number:</h3>", 0, "R", false);
		$pdf->SetXY(inches(5.5), $y);
		$pdf->multicell->multiCell(inches(2), $pdf->lh, "<h1>$claim_number</h1>", 0, "L", false);

		$pdf->SetXY(inches(.5), $y);
		$pdf->multicell->multiCell(inches(1), $pdf->lh, "<h3>Insured:</h3>", 0, "R", false);
		$pdf->SetXY(inches(1.75), $y);
		$pdf->multicell->multiCell(inches(2), $pdf->lh, "<h3>$insured</h3>", 0, "L", false);

		$pdf->SetXY(inches(.5), $y + $pdf->lh);
		$pdf->multicell->multiCell(inches(1), $pdf->lh, "<h3>Address:</h3>", 0, "R", false);
		$pdf->SetXY(inches(1.75), $y + $pdf->lh);
		$pdf->multicell->multiCell(inches(2), $pdf->lh, "<h3>$insured_address1\n$insured_address2</h3>", 0, "L", false);

		return $pdf->GetY() + 6;
	}
	protected function estimator($pdf, $y, $est_name, $est_position, $est_phone, $est_email)
	{
		$pdf->SetXY(inches(.5), $y);
		$pdf->multicell->multiCell(inches(1), $pdf->lh, "<h3>Estimator:</h3>", 0, "R", false);
		$pdf->SetXY(inches(1.75), $y);
		$pdf->multicell->multiCell(inches(2), $pdf->lh, "<h3>$est_name</h3>", 0, "L", false);

		$pdf->SetXY(inches(.5), $y + $pdf->lh);
		$pdf->multicell->multiCell(inches(1), $pdf->lh, "<h3>Position:</h3>", 0, "R", false);
		$pdf->SetXY(inches(1.75), $y + $pdf->lh);
		$pdf->multicell->multiCell(inches(2), $pdf->lh, "<h3>$est_position</h3>", 0, "L", false);

		$pdf->SetXY(inches(4), $y);
		$pdf->multicell->multiCell(inches(1.25), $pdf->lh, "<h3>Business:</h3>", 0, "R", false);
		$pdf->SetXY(inches(5.5), $y);
		$pdf->multicell->multiCell(inches(2), $pdf->lh, "<h3>$est_phone</h3>", 0, "L", false);

		$pdf->SetXY(inches(4), $y + $pdf->lh);
		$pdf->multicell->multiCell(inches(1.25), $pdf->lh, "<h3>Email:</h3>", 0, "R", false);
		$pdf->SetXY(inches(5.5), $y + $pdf->lh);
		$pdf->multicell->multiCell(inches(2), $pdf->lh, "<h3>$est_email</h3>", 0, "L", false);

		return $pdf->GetY() + 12;
	}
	protected function heading($pdf, $y, $heading)
	{
		$y = $this->check_pagebreak($pdf, $y, 40);
		$pdf->SetXY(inches(.5), $y);
		$pdf->multicell->multiCell(0, 5, "<h1>$heading</h1>");

		$pdf->SetDrawColor(50);
		$pdf->SetLineWidth(.5);
		$pdf->Line(inches(.5), $pdf->GetY() + 1.5, inches(8), $pdf->GetY() + 1.5);

		return $pdf->GetY() + 6;
	}
	protected function sub_heading($pdf, $y, $sub_heading)
	{
		$y = $this->check_pagebreak($pdf, $y, 30);
		$pdf->SetXY(inches(.5), $y);
		$pdf->multicell->multiCell(0, 5, "<h2>$sub_heading</h2>");

		return $pdf->GetY() + 2;
	}
	protected function table($pdf, $y, $left, $right, $table)
	{
		$columns = columns($pdf, $table);

		// Table
		$pdf->SetFillColor(200,200,200);
		$pdf->SetXY(inches(.5), $y);
		$pdf->multicell->multiCell($right - $left, $pdf->lht, " ", 0, "L", true);

		foreach ($table as $row) {
			$this->table_row($pdf, $y, inches(.5), inches(8), $columns, $row);
			$y += $pdf->lht;
		}

		return $y + 5;
	}
	protected function table_row($pdf, $y, $left, $right, $columns, $row)
	{
		$y = $this->check_pagebreak($pdf, $y);
		$width = $right - $left;
		$pdf->SetDrawColor(180);
		$pdf->SetLineWidth(.1);

		for ($i=0; $i<count($columns[1]); $i++) {
			$pdf->SetXY($left, $y);
			$pdf->multicell->multiCell($width * $columns[1][$i], $pdf->lht, iconv('UTF-8', 'windows-1252', $row[$i]), "LTRB", "C");
			$left += $width * $columns[1][$i];
		}
	}
	protected function check_pagebreak($pdf, $y, $breakpoint = 20)
	{
		if ($y > ($pdf->GetPageHeight() - $breakpoint)) {
			$y = inches(.5);
			$pdf->AddPage($pdf->orien, $pdf->size);
		}
		return $y;	
	}
}
