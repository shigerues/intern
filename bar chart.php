<?php
require('../assets/fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->Image('../assets/img/logo telkomm.png', 10, 6, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Minat & Bakat', 0, 1, 'C');
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->SetX(0);
        $this->Cell(0, 7, 'Chandra  beehive.binus.org  Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function RoundedRect($x, $y, $w, $h, $r, $style = '', $roundCorners = '1111') {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F') {
            $op = 'f';
        } elseif ($style == 'FD' || $style == 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }
        $MyArc = 4 / 3 * (sqrt(2) - 1);

        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));
        
        $xc = $x + $w - $r;
        $yc = $y + $r;
        if ($roundCorners[0] == '1') {
            $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
            $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
        } else {
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $y) * $k));
        }

        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        if ($roundCorners[1] == '1') {
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
            $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
        } else {
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
        }

        $xc = $x + $r;
        $yc = $y + $h - $r;
        if ($roundCorners[2] == '1') {
            $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
            $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
        } else {
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - ($y + $h)) * $k));
        }

        $xc = $x + $r;
        $yc = $y + $r;
        if ($roundCorners[3] == '1') {
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
            $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        } else {
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $y) * $k));
        }

        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

    function DrawGraph() {
        $this->Line(50, 50, 50, 180);
        $this->Line(50, 180, 180, 180);

        $labelsY = [
            'Realistic', 'Investigative', 'Artistic', 'Social', 
            'Enterprising', 'Conventional', 'Concern', 
            'Confidence', 'Conception', 'Control'
        ];

        for ($i = 0; $i < count($labelsY); $i++) {
            $this->SetXY(15, 30+ ($i * 13));
            $this->Cell(40, 45, $labelsY[$i], 0, 0, 'L');
        }

        // Set values with Investigative and Concern equal to Realistic
        $values = [6, 5, -4, -3, 5, 1, 1, 4, 6, -3];

        $positiveColor = [192, 192, 192]; // Green
        $negativeColor = [105, 105, 105]; // Red

        for ($i = 0; $i < count($values); $i++) {
            $barLength = $values[$i] * 10;
            $barHeight = 8;
            $barYPos = 50 + ($i * 13);

            if ($barLength < 0) {
                $this->SetFillColor($negativeColor[0], $negativeColor[1], $negativeColor[2]);
                // Lengkungan kiri atas dan kiri bawah
                $this->RoundedRect(115 + $barLength, $barYPos, abs($barLength), $barHeight, 3, 'F', '0011'); // Lengkungan lengkap
            } else {
                $this->SetFillColor($positiveColor[0], $positiveColor[1], $positiveColor[2]);
                // Lengkungan kanan atas dan kanan bawah
                $this->RoundedRect(115, $barYPos, $barLength, $barHeight, 3, 'F', '1100'); // Lengkungan lengkap
            }
        }

        $this->Line(115, 50, 115, 180); 
        $this->SetFont('Arial', '', 10);
        for ($i = -6; $i <= 6; $i++) {
            $this->SetXY(115 + ($i * 10) - 2, 180);
            $this->Cell(4, 4, $i, 0, 0, 'C');
        }
        $this->Ln(50);
    }

    function AddExplanation($explanation) {
        // Set font
        $this->SetFont('Arial', '', 12);
        // Output explanation
        $this->MultiCell(180, 10, $explanation);
        // Line break
        $this->Ln(5); // Adjusted line spacing
    }

    function AddIconDescription($symbol, $title, $description) {
        // Set font for title
        $this->SetFont('Arial', 'B', 10);
        // Output title with symbol
        $this->Cell(0, 10, $symbol . ' ' . $title, 0, 1);
        // Set font for description
        $this->SetFont('Arial', '', 10);
        // Output description
        $this->MultiCell(180, 5, $description);
        // Line break
        $this->Ln(5); // Adjusted line spacing
    }
}

// Create a PDF instance
$pdf = new PDF();

// Add a page
$pdf->AddPage();

// Draw graph
$pdf->DrawGraph();

// Output PDF
$pdf->Output();
?>