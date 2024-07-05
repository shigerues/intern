<?php
require('../assets/fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Funnel Chart of online shopping', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function FunnelChart($data)
    {
        $this->SetFont('Arial', '', 10);

        $colors = [
            [70, 130, 180],  // Sent - Blue
            [95, 158, 160],  // Viewed - CadetBlue
            [100, 149, 237], // Clicked - CornflowerBlue
            [255, 215, 0],   // Add to Cart - Gold
            [255, 140, 0]    // Purchased - DarkOrange
        ];

        $totalHeight = 120; // total height of the funnel chart
        $maxWidth = 100;    // max width of the funnel chart
        $xStart = 30;       // x start position (shifted to the left)
        $yStart = 50;       // y start position
        $stepHeight = $totalHeight / count($data); // height of each funnel step

        $previousWidth = $maxWidth;

        foreach ($data as $key => $value) {
            $percentage = $value[1];
            $width = $maxWidth * ($percentage / 100);

            $this->SetFillColor($colors[$key][0], $colors[$key][1], $colors[$key][2]);
            $xPosition = $xStart + (($maxWidth - $width) / 2); // center the rectangle
            $this->Rect($xPosition, $yStart, $width, $stepHeight, 'F');
            $this->SetXY($xStart + $maxWidth + 10, $yStart + ($stepHeight / 4));
            $this->Cell(30, 10, $value[0], 0, 0, 'L');
            $this->Cell(30, 10, number_format($percentage, 2) . '%', 0, 1, 'L');

            $yStart += $stepHeight;
        }
    }
}

$pdf = new PDF();
$pdf->AddPage();
$data = [
    ['Sent', 100],
    ['Viewed', 68.22],
    ['Clicked', 29.39],
    ['Add to Cart', 10.75],
    ['Purchased', 9.95]
];
$pdf->FunnelChart($data);

    

$pdf->Output();
?>
