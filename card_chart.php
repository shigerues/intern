<?php
require('../assets/fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        // Optional: add a header to the PDF
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function AddBackground($backgroundPath)
    {
        // Add a background image
        $this->Image($backgroundPath, 0, 0, $this->GetPageWidth(), $this->GetPageHeight());
    }

    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        $this->_Arc($xc+$r*$MyArc, $yc-$r, $xc+$r, $yc-$r*$MyArc, $xc+$r, $yc);
        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k, ($hp-$yc)*$k));
        $this->_Arc($xc+$r, $yc+$r*$MyArc, $xc+$r*$MyArc, $yc+$r, $xc, $yc+$r);
        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-($y+$h))*$k));
        $this->_Arc($xc-$r*$MyArc, $yc+$r, $xc-$r, $yc+$r*$MyArc, $xc-$r, $yc);
        $xc = $x+$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k, ($hp-$yc)*$k ));
        $this->_Arc($xc-$r, $yc-$r*$MyArc, $xc-$r*$MyArc, $yc-$r, $xc, $yc-$r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

    function Card($x, $y, $iconPath, $name, $position, $id, $backgroundColor = array(255, 255, 255))
    {
        // Card border and background color with rounded corners
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.5);
        $this->SetFillColor($backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
        $this->RoundedRect($x, $y, 90, 60, 5, 'DF');

        // Icon (Image)
        list($imageWidth, $imageHeight) = getimagesize($iconPath);
        $aspectRatio = $imageWidth / $imageHeight;
        $newWidth = 20;
        $newHeight = $newWidth / $aspectRatio;
        if ($newHeight > 20) {
            $newHeight = 20;
            $newWidth = $newHeight * $aspectRatio;
        }
        $this->Image($iconPath, $x + 5, $y + 20, $newWidth, $newHeight);  // Adjust the position and size as needed

        // Name
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY($x + 30, $y + 20);
        $this->Cell(55, 10, $name, 0, 1, 'L');

        // Position
        $this->SetFont('Arial', '', 12);
        $this->SetXY($x + 30, $y + 30);
        $this->Cell(55, 10, $position, 0, 1, 'L');

        // ID
        $this->SetFont('Arial', '', 12);
        $this->SetXY($x + 30, $y + 40);
        $this->Cell(55, 10, "ID: " . $id, 0, 1, 'L');
    }
}

$pdf = new PDF();
$pdf->AddPage();

// Add background to the page
$backgroundPath = '../assets/img/design.png';  // Update this to the correct path of your background image
$pdf->AddBackground($backgroundPath);

// Title of the document
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Employee Cards', 0, 1, 'C');
$pdf->Ln(10);  // Add a line break for spacing

// Data untuk kartu-kartu
$cards = array(
    array('name' => 'Haechan', 'position' => 'Manager', 'id' => '12345', 'iconPath' => '../assets/img/hc.png', 'backgroundColor' => array(150, 200, 255)),
    array('name' => 'Mark Lee', 'position' => 'Developer', 'id' => '67890', 'iconPath' => '../assets/img/mark.png', 'backgroundColor' => array(255, 200, 150)),
    array('name' => 'Louis', 'position' => 'Designer', 'id' => '24680', 'iconPath' => '../assets/img/louis.png', 'backgroundColor' => array(200, 255, 150)),
    array('name' => 'Jaehyun', 'position' => 'Analyst', 'id' => '13579', 'iconPath' => '../assets/img/jae.png', 'backgroundColor' => array(200, 150, 255)),
);

// Koordinat awal untuk kartu-kartu
$cardX = 10;
$cardY = 30;
$cardWidth = 90;
$cardHeight = 60;
$marginX = 10;
$marginY = 10;

foreach ($cards as $card) {
    $pdf->Card($cardX, $cardY, $card['iconPath'], $card['name'], $card['position'], $card['id'], $card['backgroundColor']);
    $cardX += $cardWidth + $marginX;
    if ($cardX + $cardWidth > $pdf->GetPageWidth() - $marginX) {
        $cardX = 10;
        $cardY += $cardHeight + $marginY;
    }
    if ($cardY + $cardHeight > $pdf->GetPageHeight() - $marginY) {
        $pdf->AddPage();
        $pdf->AddBackground($backgroundPath);
        $cardY = 30;
    }
}

$pdf->Output();
?>
