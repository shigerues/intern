<?php
require('../assets/fpdf/fpdf.php');

class PDF extends FPDF {
    // Header halaman
    function Header() {
        $this->Image('../assets/img/binus.png', 88, 50, 35); // Sesuaikan posisi dan ukuran jika perlu
        $this->SetFont('Arial', 'B', 27);
        $this->SetTextColor(77, 79, 80);
        $this->Cell(80);
        $this->Cell(30, 150, 'LAPORAN POTENSI DIRI', 0, 1, 'C');
        $this->Ln(20);
    }

    // Footer halaman
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'C');
        if ($this->PageNo() == 1) {
            $this->SetY(-26);
            $this->Cell(0, 10, 'BEELIFE.BINUS.ORG', 0, 0, 'C');
        }
    }

    function AddCircles($x, $y, $radius, $spacing, $rows, $cols, $color) {
        $this->SetFillColor($color[0], $color[0], $color[0]);
        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $cols; $col++) {
                $cx = $x + $col * ($radius * 2 + $spacing);
                $cy = $y + $row * ($radius * 2 + $spacing);
                $this->Circle($cx, $cy, $radius, 'F');
            }
        }
    }

    function Circle($x, $y, $r, $style = 'D') {
        $this->Ellipse($x, $y, $r, $r, $style);
    }

    function Ellipse($x, $y, $rx, $ry, $style = 'D') {
        if ($style == 'F') {
            $style = 'f';
        } elseif ($style == 'DF' || $style == 'FD') {
            $style = 'B';
        }
        $op = $style == 'f' ? 'f' : ($style == 'B' ? 'B' : 'S');
        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;
        $this->_out(sprintf('%.2F %.2F m', ($x + $rx) * $this->k, ($this->h - $y) * $this->k));
        $this->_Arc($x + $rx, $y - $ly, $x + $lx, $y - $ry, $x, $y - $ry);
        $this->_Arc($x - $lx, $y - $ry, $x - $rx, $y - $ly, $x - $rx, $y);
        $this->_Arc($x - $rx, $y + $ly, $x - $lx, $y + $ry, $x, $y + $ry);
        $this->_Arc($x + $lx, $y + $ry, $x + $rx, $y + $ly, $x + $rx, $y);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

    function Gradient($x, $y, $w, $h, $col1, $col2, $arah = 'vertical') {
        if ($arah == 'vertical') {
            $this->Rect($x, $y, $w, $h, 'F');
            for ($i = 0; $i < $h; $i++) {
                $r = $col1[0] + ($col2[0] - $col1[0]) * $i / $h;
                $g = $col1[1] + ($col2[1] - $col1[1]) * $i / $h;
                $b = $col1[2] + ($col2[2] - $col1[2]) * $i / $h;
                $this->SetFillColor($r, $g, $b);
                $this->Rect($x, $y + $i, $w, 1, 'F');
            }
        } else {
            $this->Rect($x, $y, $w, $h, 'F');
            for ($i = 0; $i < $w; $i++) {
                $r = $col1[0] + ($col2[0] - $col1[0]) * $i / $w;
                $g = $col1[1] + ($col2[1] - $col1[1]) * $i / $w;
                $b = $col1[2] + ($col2[2] - $col1[2]) * $i / $w;
                $this->SetFillColor($r, $g, $b);
                $this->Rect($x + $i, $y, 1, $h, 'F');
            }
        }
    }

    function TextWithBackground($x, $y, $teks) {
        $col1 = [77, 79, 80];
        $col2 = [255, 255, 255];
        $lebar = $this->GetStringWidth($teks) + 6;
        $tinggi = 10;
        $this->Gradient($x, $y, $lebar, $tinggi, $col1, $col2, 'horizontal');
        $this->SetTextColor(158, 51, 51);
        $this->SetFont('Arial', 'B', 17);
        $this->Text($x + 3, $y + 7, $teks);
    }
}

$pdf = new PDF();
$pdf->AddPage();

// Path to the images
$image1Path = '../assets/img/neww-02.png';
$image2Path = '../assets/img/neww-06.png';
$image3Path = '../assets/img/neww-03.png';
$image4Path = '../assets/img/neww-05.png';
$image5Path = '../assets/img/01.png';

// Check if the images exist
if (file_exists($image1Path) && file_exists($image2Path) && file_exists($image3Path) && file_exists($image4Path) && file_exists($image5Path)) {
    $pdf->Image($image5Path, 0, 0, 200, 50); // Add the fifth image first
    $pdf->AddCircles(10, 10, 2, 3, 5, 5, [252, 111, 173]); // Add the circles on top of the fifth image
    $pdf->Image($image1Path, 15, 90, 170, 100); // Add the first image
    $pdf->Image($image2Path, 60, 215, 90, 20); // Add the second image as background
    $pdf->Image($image3Path, 150, 260, 68, 50); // Add the third image
    $pdf->Image($image4Path, 28, 244, 155, 30); // Add the rectangle image as background for the text box
} else {
    die('One or more images not found.');
}

// Adding the text on top of the background image
$pdf->SetTextColor(255, 255, 255); // Set text color
$pdf->SetFont('Arial', 'B', 14); // Set font
$pdf->Text(78, 223, 'Madeline Emily Lefevre'); // Adjusted position to center the text on the background image
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Text(94, 232, '1 July 2024');

// Adding the new text on top of neww-05 png
$pdf->SetXY(30, 248);
$pdf->SetTextColor(255, 250, 255); // Set text color to white
$pdf->SetFont('Arial', '', 10); // Set font for the text
$text = "Laporan ini tidak dapat digunakan untuk mengidentifikasi status psikologis, kesehatan mental atau masalah kesehatan pada umumnya. Tanggung jawab atas tindakan keputusan yang dilakukan setelah membaca laporan ini sepenuhnya berada pada pengguna laporan. Di BINUS University, kami percaya bahwa keputusan masa depanmu ada di tanganmu sendiri.";
$pdf->MultiCell(150, 5, $text);

$pdf->Output();
?>
