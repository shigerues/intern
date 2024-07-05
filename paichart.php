<?php
require('../assets/fpdf/fpdf.php');

class PDF_Diag extends FPDF {
    var $legends;
    var $wLegend;
    var $sum;
    var $NbVal;

    function Header()
{
    // Add logo or any image
    $this->Image('../assets/img/cover4.jpg', 0, 0, 210, 297);

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


    function PieChart($w, $h, $data, $format, $colors=null) {
        $this->SetFont('Courier', '', 10);
        $this->SetLegends($data,$format);
        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;
        $hLegend = 5;
        $radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
        $radius = floor($radius / 2);
        $XDiag = $XPage + $margin + $radius;
        $YDiag = $YPage + $margin + $radius;

        if($colors == null) {
            for($i = 0; $i < $this->NbVal; $i++) {
                $gray = $i * intval(255 / $this->NbVal);
                $colors[$i] = array($gray,$gray,$gray);
            }
        }

        $this->SetLineWidth(0.2);
        $angleStart = 0;
        $angleEnd = 0;
        $i = 0;
        foreach($data as $val) {
            $angle = ($val * 360) / doubleval($this->sum);
            if ($angle != 0) {
                $angleEnd = $angleStart + $angle;
                $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
                $this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
                $angleStart += $angle;
            }
            $i++;
        }

        $this->SetFont('Courier', '', 10);
        $x1 = $XPage + 2 * $radius + 4 * $margin;
        $x2 = $x1 + $hLegend + $margin;
        $y1 = $YDiag - $radius + (2 * $radius - $this->NbVal*($hLegend + $margin)) / 2;
        for($i=0; $i<$this->NbVal; $i++) {
            $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
            $this->Rect($x1, $y1, $hLegend, $hLegend, 'DF');
            $this->SetXY($x2,$y1);
            $this->Cell(0,$hLegend,$this->legends[$i]);
            $y1+=$hLegend + $margin;
        }
    }

    function SetLegends($data, $format) {
        $this->legends=array();
        $this->wLegend=0;
        $this->sum=array_sum($data);
        $this->NbVal=count($data);

        foreach($data as $l=>$val) {
            $p=sprintf('%.2f',$val/$this->sum*100).'%';
            $legend=str_replace(array('%l','%v','%p'),array($l,$val,$p),$format);
            $this->legends[]=$legend;
            $this->wLegend=max($this->GetStringWidth($legend),$this->wLegend);
        }
    }

    function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90) {
        $d0 = $a - $b;
        if($cw){
            $d = $b;
            $b = $o - $a;
            $a = $o - $d;
        }else{
            $b += $o;
            $a += $o;
        }
        while($a<0)
            $a += 360;
        while($a>360)
            $a -= 360;
        while($b<0)
            $b += 360;
        while($b>360)
            $b -= 360;
        if ($a > $b)
            $b += 360;
        $b = $b/360*2*M_PI;
        $a = $a/360*2*M_PI;
        $d = $b - $a;
        if ($d == 0 && $d0 != 0)
            $d = 2*M_PI;
        $k = $this->k;
        $hp = $this->h;
        if (sin($d/2))
            $MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
        else
            $MyArc = 0;
            $this->_out(sprintf('%.2F %.2F m', ($xc)*$this->k, ($this->h-$yc)*$this->k));
            $this->_out(sprintf('%.2F %.2F l', ($xc + $r * cos($a)) * $this->k, ($this->h - ($yc - $r * sin($a))) * $this->k));

        if ($d < M_PI/2){
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
        }else{
            $b = $a + $d/4;
            $MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            if ($d < M_PI / 2) {
                $this->_Arc(
                    $xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                    $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                    $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                    $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                    $xc + $r * cos($b),
                    $yc - $r * sin($b)
                );
            } else {
                $b = $a + $d / 4;
                $MyArc = 4 / 3 * (1 - cos($d / 8)) / sin($d / 8) * $r;
                for ($i = 0; $i < 4; $i++) {
                    $this->_Arc(
                        $xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                        $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                        $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                        $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                        $xc + $r * cos($b),
                        $yc - $r * sin($b)
                    );
                    $a = $b;
                    $b = $a + $d / 4;
                }
            }
            
        }
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='b';
        else
            $op='s';
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3 ) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1*$this->k,
            ($h-$y1)*$this->k,
            $x2*$this->k,
            ($h-$y2)*$this->k,
            $x3*$this->k,
            ($h-$y3)*$this->k));
    }
}

// Data untuk diagram pie
$data = array('Xiaomi' => 16.8, 'Samsung' => 30.3, 'Iphone' => 32.8, 'Oppo' => 15.9, 'Infinix' => 4.2);

$pdf = new PDF_Diag();
$pdf->AddPage();

// Menambahkan judul
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Telephone Sales Report 2024', 0, 1, 'C');
$pdf->Ln(10);

// Pie Chart
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, '1 - Pie Chart', 0, 1);
$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Cell(30, 5, 'Xiaomi:');
$pdf->Cell(15, 5, $data['Xiaomi'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Samsung:');
$pdf->Cell(15, 5, $data['Samsung'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Iphone:');
$pdf->Cell(15, 5, $data['Iphone'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Oppo:');
$pdf->Cell(15, 5, $data['Oppo'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Infinix:');
$pdf->Cell(15, 5, $data['Infinix'], 0, 0, 'R');
$pdf->Ln();
$pdf->Ln(8);
$pdf->SetXY(90, $valY);
$col1=array(95,158,160);
$col2=array(255,0,0);
$col3=array(240,230,140);
$col4=array(100,255,100);
$col5=array(100,255,255);
$pdf->PieChart(100, 50, $data, '%l (%p)', array($col1,$col2,$col3,$col4,$col5));
$pdf->SetXY($valX, $valY + 50);

$pdf->Output();
?>