zrttrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr<?php
require('../assets/fpdf/fpdf.php');

class PDF_Diag extends FPDF {
    var $legends;
    var $wLegend;
    var $sum;
    var $NbVal;

    function Header()
    {
        // Add logo or any image
        $this->Image('../assets/img/bg.png', 0, 0, 210, 297);
        $this->Image('../assets/img/FM.png', 10, 6, 30);

        // Set font for the title
        $this->SetFont('Arial', 'B', 12);

        // Line break
        $this->Ln(20);
    }

    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function BarChartHorizontal($w, $h, $data, $format, $colors=null) {
        $this->SetFont('Arial', '', 10);
        $this->SetLegends($data, $format);
        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 20;  // Increase margin to provide more space for labels
        $bar_height = 10; // Height of each bar
        $max_value = max($data);

        if($colors == null) {
            for($i = 0; $i < $this->NbVal; $i++) {
                $gray = $i * intval(255 / $this->NbVal);
                $colors[$i] = array($gray, $gray, $gray);
            }
        }

        // Calculate height of the bar chart
        $chart_height = ($bar_height + $margin / 2) * $this->NbVal - $margin / 2;

        // Adjust X and Y coordinates for the axes
        $xAxisStartX = $XPage + $margin - 0;
        $xAxisEndX = $XPage + $w - $margin;
        $yAxisStartY = $YPage;
        $yAxisEndY = $YPage + $chart_height;

        // Draw X and Y axis
        $this->Line($xAxisStartX, $yAxisStartY, $xAxisStartX, $yAxisEndY); // Y-axis
        $this->Line($xAxisStartX, $yAxisEndY, $xAxisEndX, $yAxisEndY); // X-axis

        $this->SetLineWidth(0.2);
        $i = 0;
        foreach($data as $key => $val) {
            $bar_width = ($val / $max_value) * ($w - 2 * $margin);
            $this->SetFillColor($colors[$i][0], $colors[$i][1], $colors[$i][2]);
            $this->Rect($XPage + $margin, $YPage, $bar_width, $bar_height, 'F');
            // Add value text inside the bar
            $this->Text($XPage + $margin + $bar_width + 5, $YPage + $bar_height / 2 + 2, $val);
            // Add label text next to the bar
            $this->Text($XPage + $margin - $this->GetStringWidth($key) - 7, $YPage + $bar_height / 2 + 2, $key);
            $YPage += $bar_height + $margin / 2;  // Adjust margin between bars
            $i++;
        }

        // Draw labels near the vertical line
        $this->SetFont('Arial', '', 8);
        $yStart = $YPage - ($bar_height + $margin / 2) / 2;
        $xAxisStartX = $XPage + $margin;
        $xAxisEndX = $XPage + $w - $margin;

        // Draw numbers below horizontal line
        $this->SetXY($xAxisStartX - 2, $yAxisEndY + 2);
        $lineX = $xAxisStartX; // X position of the vertical line

        // Calculate the position of numbers 1 to 8
        $divisionWidth = ($xAxisEndX - $xAxisStartX) / 7; // Divide by 7 for 8 numbers
        for ($i = 1; $i <= 8; $i++) {
            $xPos = $xAxisStartX + $divisionWidth * ($i - 1) - $this->GetStringWidth($i) / 2;
            $this->Text($xPos, $yAxisEndY + 6, $i);
        }
    }

    function SetLegends($data, $format) {
        $this->legends=array();
        $this->wLegend=0;
        $this->sum=array_sum($data);
        $this->NbVal=count($data);

        foreach($data as $key => $val) {
            $p=sprintf('%.2f',$val/$this->sum*100).'%';
            $legend=str_replace(array('%l','%v','%p'),array($key,$val,$p),$format);
            $this->legends[]=$legend;
            $this->wLegend=max($this->GetStringWidth($legend),$this->wLegend);
        }
    }
}

// Data untuk diagram bar
$data = array('Bag' => 16.8, 'Accessories' => 30.3, 'MakeUp' => 32.8, 'Shoes' => 15.9, 'Clothes' => 4.2);

$pdf = new PDF_Diag();
$pdf->AddPage();

// Menambahkan judul
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Sales Report 2024', 0, 1, 'C');
$pdf->Ln(10);

// Bar Chart Horizontal
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Bar Chart Horizontal', 0, 1);
$pdf->Ln(8);
$pdf->SetFont('Arial', '', 5);
$pdf->BarChartHorizontal(180, 100, $data, '%l (%v)', array(array(221, 160, 221), array(252, 192, 203), array(176, 224, 230), array(255, 255, 224), array(188, 143, 142)));

$pdf->Output();
?>
