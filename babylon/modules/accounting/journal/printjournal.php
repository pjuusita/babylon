<?php

$height=4;
$pdf = new FPDF();
$pdf->AddPage('P','A4');
$pdf->SetFont('Arial','B',10);

$pdf->SetMargins('15','15');
$pdf->SetXY('15','15');

$pdf->Cell(30,$height+2,'Paivamaara','1','0','L');
$pdf->Cell(60,$height+2,'Lahdetilili','1','0','L');
$pdf->Cell(60,$height+2,'Kohdetili','1','0','L');
$pdf->Cell(30,$height+2,'Maara','1','1','R');

$pdf->SetFont('Arial','',9);
foreach($this->registry->accountevents as $accounteventID => $accountevent) {
	if ($this->registry->selectedaccount == 0 || $accountevent->sourceaccountID == $this->registry->selectedaccount || $accountevent->targetaccountID == $this->registry->selectedaccount) {
		$pdf->Cell(30,$height,date('d.m.Y',strtotime($accountevent->eventdate)),'LR','0','L');
		$pdf->Cell(60,$height,$this->registry->accounts[$accountevent->sourceaccountID]->name,'LR','0','L');
		$pdf->Cell(60,$height,$this->registry->accounts[$accountevent->targetaccountID]->name,'LR','0','L');
		$pdf->Cell(30,$height,number_format($accountevent->amount,2,',',''),'LR','1','R');
	}
}
$pdf->Cell(180,5,'','T','0','L');
$pdf->Output();

?>