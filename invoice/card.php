<?php
require('../assets/fpdf/fpdf.php');

class PDF extends FPDF {
    function CreditCard($x, $y, $w, $h) {
        $this->SetDrawColor(210, 210, 210);
        $this->SetFillColor(255, 255, 255);
        $this->Rect($x, $y, $w, $h, 'DF');

        $this->SetLineWidth(0.1);
        $this->SetDrawColor(210, 210, 210);
        $this->SetFillColor(240, 240, 240);
        $this->Rect($x + 2, $y + 2, $w - 4, 30, 'DF');

        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(100, 100, 100);
        $this->Text($x + 7, $y + 7, 'CARDHOLDER NAME');

        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(120, 120, 120);
        $this->Text($x + 7, $y + 15, '1234 5678 9012 3456');

        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(150, 150, 150);
        $this->Text($x + 7, $y + 25, 'VALID THRU: 12/23');

        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(100, 100, 100);
        $this->Text($x + 7, $y + 35, 'VISA');

        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Text($x + 7, $y + 40, 'Lorem Ipsum');

        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(100, 100, 100);
        $this->Text($x + $w - 22, $y + 35, 'CVV');
        $this->Rect($x + $w - 25, $y + 37, 20, 10, 'D');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->CreditCard(50, 50, 85, 55);
$pdf->Output();
?>
