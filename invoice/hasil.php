<?php
require('../assets/fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
      //latar belakang gambar ini belum dibenerin lagi
      $this->SetXY(0, 0);
      $this->Image('../assets/img/okokok.png', 0, 0, $this->w, $this->h);
    

    
        // Remove logo
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Funnel Chart of Online Shopping', 0, 1, 'C');
        $this->Ln(20);
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
        $yStart = 30;       // y start position
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

    function CreateBusinessCard($judul, $name, $address, $phone, $nisn, $photo)
    {
        // Set card dimensions
        $cardWidth = 130;
        $cardHeight = 54;

        // Set margins
        $marginX = (210 - $cardWidth) / 2;
        $marginY = 160; // Adjusted to be below the funnel chart

        // Draw card outline
        $this->SetXY($marginX, $marginY);
        $this->SetLineWidth(0.5);
        $this->Rect($marginX, $marginY, $cardWidth, $cardHeight);

        // Insert image
        $this->Image($photo, $marginX + 5, $marginY + 5, 40, 40);

        // Set font
        // judul
        $this->SetXY($marginX + 46, $marginY + 5);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, " $judul", 0, 1);

        // Name
        $this->SetXY($marginX + 50, $marginY + 15);
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 10, "Nama: $name", 0, 1);

        // Address
        $this->SetXY($marginX + 50, $marginY + 23);
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 10, "Alamat: $address", 0, 1);

        // Phone
        $this->SetXY($marginX + 50, $marginY + 30);
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 10, "Nomor Telepon: $phone", 0, 1);

        // NISN
        $this->SetXY($marginX + 50, $marginY + 38);
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 10, "NISN: $nisn", 0, 1);
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
        $this->AddPage();
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Tingkat Keselamatan Mobil', 0, 1, 'C');
        $this->Ln(20);

        $this->Line(50, 50, 50, 200);
        $this->Line(50, 200, 180, 200);

        $labelsY = [
            'Ertiga', 'Inova', 'Fortuner', 'Pajero', 
            'Brio', 'Conventional', '911 Turbo', 
            'Ft86', 'Ioniq 6', 'Ioniq 5'
        ];

        for ($i = 0; $i < count($labelsY); $i++) {
            $this->SetXY(10, 55 + ($i * 13));
            $this->Cell(40, 10, $labelsY[$i], 0, 0, 'R');
        }

        $values = [2, 6, -2, -2, -6, -2, 4, 2, 6, -4];

        $positiveColor = [25, 200, 77]; // Green
        $negativeColor = [207, 19, 2]; // Red

        for ($i = 0; $i < count($values); $i++) {
            $barLength = $values[$i] * 10;
            $barHeight = 8;
            $barYPos = 55 + ($i * 13);

            if ($barLength < 0) {
                $this->SetFillColor($negativeColor[0], $negativeColor[1], $negativeColor[2]);
                $this->RoundedRect(115 + $barLength, $barYPos, abs($barLength), $barHeight, 3, 'F', '0011');
            } else {
                $this->SetFillColor($positiveColor[0], $positiveColor[1], $positiveColor[2]);
                $this->RoundedRect(115, $barYPos, $barLength, $barHeight, 3, 'F', '1100');
            }
        }

        $this->Line(115, 50, 115, 200); 
        $labelsX = [
            '-6' => 'Sangat Rendah',
            '-5' => '',
            '-4' => ' Rendah',
            '-3' => '',
            '-2' => 'Sedikit rendah',
            '-1' => '',
            '0'  => 'Netral',
            '1'  => '',
            '2'  => 'baik',
            '3'  => '',
            '4'  => 'lumayan',
            '5'  => '',
            '6'  => 'Sangat baik'
        ];

        $this->SetFont('Arial', '', 10);
        for ($i = -6; $i <= 6; $i++) {
            $this->SetXY(115 + ($i * 10) - 5, 202);
            $this->Cell(10, 4, $labelsX[(string)$i], 0, 0, 'C');
        }
        $this->Ln(50);
    }
}

// Create a PDF instance
$pdf = new PDF();

// Add a page for the funnel chart
$pdf->AddPage();

// Data for the funnel chart
$data = [
    ['Sent', 100],
    ['Viewed', 68.22],
    ['Clicked', 29.39],
    ['Add to Cart', 10.75],
    ['Purchased', 9.95]
];

// Draw the funnel chart
$pdf->FunnelChart($data);

// Data for the business card
$judul = "KARTU PELAJAR SMK TELKOM JAKARTA";
$name = "Mohammad Zaidan";
$address = "Cluster Cipondoh Indah, Tangerang";
$phone = "08999973";
$nisn = "539221220";
$photo = "logo telkomm.png"; // Ganti dengan path ke foto Anda

// Create the business card
$pdf->CreateBusinessCard($judul, $name, $address, $phone, $nisn, $photo);

// Draw the bar chart
$pdf->DrawGraph();

// Output the PDF
$pdf->Output();
?>
